<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Utilisateur;
use App\Entity\WpUsers;
use App\Form\SearchForm;
use App\Form\UtilisateurType;
use App\Repository\WpUsersRepository;
use App\Repository\UtilisateurRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class AdminController extends AbstractController
{

    // Afficher les villes
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/utilisateur", name="utilisateur_list")
     */
    public function list(UtilisateurRepository $utilisateurRepository,
                         Request $request,
                         EntityManagerInterface $entityManager,
                         PaginatorInterface $paginator

    ): Response
    {
        $data_utilisateurs = $utilisateurRepository->findAll();
        $data = $utilisateurRepository->findAll();
        $filter = new SearchData();
        $form_filter = $this->createForm(SearchForm::class, $filter);
        $form_filter->handleRequest($request);
        $data = $utilisateurRepository->findSearch($filter);
        $utilisateurs = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );
        $utilisateur = new Utilisateur();

        return $this->render('admin/admin_list_utilisateur.html.twig', [
            'utilisateur'=>$utilisateur,
            'utilisateurs'=>$utilisateurs,
            'form_filter' => $form_filter->createView()
        ]);
    }


    // Stats des villes
    public function statistiques(UtilisateurRepository $utilisateurRepository): Response
    {

        $utilisateurs_data = $utilisateurRepository->findAll();

        //nombre total
        $nbre_total = sizeof($utilisateurs_data);
        $nbre_sanit = 0;
        $nbre_sanit_chauff = 0;

        //nombre de sanitaire simple
        //-------------- GRAPH PRODUIT VISE ------------------
        for ($i=0; $i<$nbre_total; $i++){
            $produit_vise = $utilisateurs_data[$i]->getProduitVise();
            if ( $produit_vise == "Eau chaude sanitaire et chauffage") {
                $nbre_sanit_chauff++;
            } else {
                $nbre_sanit++;
            }
        }
        //-------------- GRAPH DEPARTEMENT ------------------

        $tableauCP = [];
        for ($i=0; $i<$nbre_total; $i++) {
            $cp = $utilisateurs_data[$i]->getCodePostal();
            $dep = substr($cp, -5, 2);
            $departement = intval($dep);
            $tableauCP[$i] = $departement;
        }


        $tableau_sans_doublon = [];

        // ------------RECUP LES NOM DES DEPARTEMENTS---------
        // parcourir chaque valeur
        for ($i=0; $i<sizeof($tableauCP); $i++) {
            if ($i == 0) {
                // mettre la premiere dans un tableau
                $tableau_sans_doublon[] = $tableauCP[$i];
            } else {
                // comparé la seconde avec la précédente
                if ($tableauCP[$i] != $tableauCP[$i-1]) {
                    // si elle est differente mettre dans le tableau
                    $tableau_sans_doublon[] = $tableauCP[$i];
                }
            }
        }
        $tableau_sans_doublon = array_unique($tableau_sans_doublon);

        $tableau_sans_doublon = array_values($tableau_sans_doublon);

        // ------------RECUP LES NOMBRES DE DEPARTEMENTS---------
        $count_dep = array_count_values($tableauCP);

        for ($i=0; $i<sizeof($count_dep); $i++) {
            $compteur_departement[] = $count_dep[$tableau_sans_doublon[$i]];
        }

        return $this->render('admin/graphique.html.twig', [
            'nbre_sanit'=> $nbre_sanit,
            'nbre_chauf'=> $nbre_sanit_chauff,
            'tableau_sans_doublon'=> json_encode($tableau_sans_doublon),
            'tableau_sans_doublon2'=> $tableau_sans_doublon,
            'count_dep'=> json_encode($compteur_departement)
        ]);
    }

    // Afficher un profil
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/utilisateur/profil/{id}", name="utilisateur_profil", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur, WpUsersRepository $wpUsersRepository): Response
    {


        $fournisseursList = $wpUsersRepository->findAll();

        // requete pour avoir les professionnels logué sur le site top enr
        $fournisseur = new WpUsers();


        return $this->render('admin/utilisateur_Details.html.twig', [
            'utilisateur' => $utilisateur,
            'fournisseursList' => $fournisseursList,
            'fournisseur' => $fournisseur
        ]);
    }

    // Supprimer un utilisateur
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/utilisateur/delete/{id}", name="utilisateur_delete")
     */
    public function delete(Request $request, Utilisateur $id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute("utilisateur_list");
    }

    // Editer un utilisateur
    /**
     *
     * @Route("/admin/utilisateur/update/{id}", name="utilisateur_edit")
     */
    public function update(Utilisateur $id, Request $request):Response
    {

        $utilisateurForm = $this->createForm(UtilisateurType::class, $id);
        $utilisateurForm->handleRequest($request);
        if($utilisateurForm->isSubmitted() && $utilisateurForm->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $utilisateurForm->getData()->setNom(strtoupper($utilisateurForm->getData()->getNom()));
            $utilisateurForm->getData()->setPrenom(ucfirst(strtolower($utilisateurForm->getData()->getPrenom())));
            $em->flush();

            $this->addFlash('success', 'Les informations ont été modifié avec succès!');
            return $this->redirectToRoute("utilisateur_list");
        }
        return $this->render('admin/utilisateur_edit.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/utilisateur/template-rappel/{id}", name="mail_rappel", methods={"GET"})
     */
    public function templateRappel(Utilisateur $utilisateur): Response
    {

        return $this->render('email/contact-rappel.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/utilisateur/template/{id}", name="mail", methods={"GET"})
     */
    public function template(Utilisateur $utilisateur): Response
    {
        return $this->render('email/contact.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/sendFournisseur/{id}/{idUser}", name="send_fournisseur")
     */
    public function SendMailFounisseur(Request $request,WpUsers  $fournisseur, Utilisateur $idUser, MailerService $mailerService)
    {

        $email = $fournisseur->getUserEmail();
        $nomFournisseur = $fournisseur->getDisplayName();

        $nomUtilisateur = $idUser->getNom();
        $prenomUtilisateur = $idUser->getPrenom();
        $telUtilisateur = $idUser->getTel();
        $emailUtilisateur = $idUser->getEmail();
        $cpUtilisateur = $idUser->getCodePostal();
        $villeUtilisateur = $idUser->getVille();

        $proprioUtilisateur = $idUser->getProprietaire();
        $typeBienUtilisateur = $idUser->getTypeBien();
        $ancienneteUtilisateur = $idUser->getAncienneteEligible();
        $produitViseUtilisateur = $idUser->getProduitVise();
        $energieUtilisateur = $idUser->getEnergie();
        $chauffageUtilisateur = $idUser->getChauffage();
        $nbreSalleBainUtilisateur = $idUser->getNbreSalleBain();
        $nbreFoyerUtilisateur = $idUser->getNbrePersFoyer();
        $rfrUtilisateur = $idUser->getRevenuFiscal();

        $prime_renov = $idUser->getResultat()->getPrimeRenov();
        $cee = $idUser->getResultat()->getCee();
        $cdpc = $idUser->getResultat()->getCdpChauffage();
        $total = $idUser->getResultat()->getMontantTotal();

        try {
            $mailerService->send("Fiche de simulation client", "contact@top-enr.com", $email, "email/fournisseur_mail.html.twig",
                [
                    // ajouter tout les infos necessaires au mail
                    'fournisseur' => $fournisseur,
                    '$idUser' => $idUser,
                    "nomFournisseur" => $nomFournisseur,

                    //contact
                    "nomUtilisateur" => $nomUtilisateur,
                    "prenomUtilisateur" => $prenomUtilisateur,
                    "telUtilisateur" => $telUtilisateur,
                    "emailUtilisateur" => $emailUtilisateur,
                    "cpUtilisateur" => $cpUtilisateur,
                    "villeUtilisateur" => $villeUtilisateur,
                    "dateSimulation" => $idUser->getDateSimulation(),

                    // informations
                    "proprieteUtilisateur" => $proprioUtilisateur,
                    "typeBienUtilisateur" => $typeBienUtilisateur,
                    "ancienneteUtilisateur" => $ancienneteUtilisateur,
                    "produitUtilisateur" => $produitViseUtilisateur,
                    "energieUtilisateur" => $energieUtilisateur,
                    "chauffageUtilisateur" => $chauffageUtilisateur,
                    "nbreSalleBainUtilisateur" => $nbreSalleBainUtilisateur,
                    "nbreFoyerUtilisateur" => $nbreFoyerUtilisateur,
                    "rfrUtilisateur" => $rfrUtilisateur,

                    //resultat
                    "prime_renov" => $prime_renov,
                    "prime_cee" => $cee,
                    "prime_fioul" => $cdpc,
                    "total" => $total,
                ]
            );
        } catch (TransportExceptionInterface $e) {

        } catch (LoaderError $e) {

        } catch (RuntimeError $e) {

        } catch (SyntaxError $e) {

        }

//        return $this->redirectToRoute("utilisateur_list");


        // --------- TEST RENDER MAIL------
        // --------- INLINER https://htmlemail.io/inline/ ---------

        /*return $this->render('email/fournisseur_mail.html.twig', [
            'fournisseur' => $fournisseur,
            '$idUser' => $idUser,
            "nomFournisseur" => $nomFournisseur,

            //contact
            "nomUtilisateur" => $nomUtilisateur,
            "prenomUtilisateur" => $prenomUtilisateur,
            "telUtilisateur" => $telUtilisateur,
            "emailUtilisateur" => $emailUtilisateur,
            "cpUtilisateur" => $cpUtilisateur,
            "villeUtilisateur" => $villeUtilisateur,
            "dateSimulation" => $idUser->getDateSimulation(),

            // informations
            "proprieteUtilisateur" => $proprioUtilisateur,
            "typeBienUtilisateur" => $typeBienUtilisateur,
            "ancienneteUtilisateur" => $ancienneteUtilisateur,
            "produitUtilisateur" => $produitViseUtilisateur,
            "energieUtilisateur" => $energieUtilisateur,
            "chauffageUtilisateur" => $chauffageUtilisateur,
            "nbreSalleBainUtilisateur" => $nbreSalleBainUtilisateur,
            "nbreFoyerUtilisateur" => $nbreFoyerUtilisateur,
            "rfrUtilisateur" => $rfrUtilisateur,

            //resultat
            "prime_renov" => $prime_renov,
            "prime_cee" => $cee,
            "prime_fioul" => $cdpc,
            "total" => $total,
        ]);*/
    }


}