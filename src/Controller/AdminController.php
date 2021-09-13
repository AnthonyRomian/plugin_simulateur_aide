<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\Graphique;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    // Afficher les villes
    /**
     * @Route("/admin/utilisateur", name="utilisateur_list")
     */
    public function list(UtilisateurRepository $utilisateurRepository,
                         Request $request,
                         EntityManagerInterface $entityManager,
                         PaginatorInterface $paginator
    ): Response
    {

        $data = $utilisateurRepository->findAll();
        $utilisateurs = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        $utilisateur = new Utilisateur();


        return $this->render('admin/admin_list_utilisateur.html.twig', [
            'utilisateur'=>$utilisateur,
            'utilisateurs'=>$utilisateurs

        ]);
    }

    /**
     * @Route("/admin/stats", name="utilisateur_stats")
     */
    public function statistiques (UtilisateurRepository $utilisateurRepository)
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

    // Supprimer un utilisateur
    /**
     * @Route("/admin/utilisateur/delete/{id}", name="utilisateur_delete")
     */
    public function delete(Request $request, Utilisateur $id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute("utilisateur_list");
    }

    /**
     * @Route("/admin/utilisateur/update/{id}", name="utilisateur_edit")
     */
    public function update(Utilisateur $id, Request $request):Response
    {

        $utilisateurForm = $this->createForm(UtilisateurType::class, $id);
        $utilisateurForm->handleRequest($request);
        if($utilisateurForm->isSubmitted() && $utilisateurForm->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Les informations ont été modifié avec succès!');
            return $this->redirectToRoute("utilisateur_list");
        }
        return $this->render('admin/utilisateur_edit.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView()
        ]);
    }

    // Afficher un profil
    /**
     * @Route("/admin/utilisateur/profil/{id}", name="utilisateur_profil", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('admin/utilisateur_Details.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
}