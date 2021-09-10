<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\Calculateur;
use App\Service\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UtilisateurController extends AbstractController
{


    /**
     * @Route("/create", name="create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        Calculateur $calculateur,
        MailerService $mailerService

    ): Response
    {
        $utilisateur = new Utilisateur();

        $utilisateurForm = $this->createForm(UtilisateurType::class, $utilisateur);
        $utilisateur->setDateSimulation(new DateTime('now'));

        $utilisateurForm->handleRequest($request);
        $agree = $utilisateur->getAgreeTerms();
        $agreeEmail = $utilisateur->getAgreeEmail();

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            if ( sizeof($agree) == 1) {

                if ( sizeof($agreeEmail) == 1) {
                    // ENVOIE MAIL OK
                    $calculateur->calculerAide($utilisateur, $entityManager, $mailerService);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();



                    $this->addFlash('success', 'Simulation réalisée avec succès');


                    return $this->redirectToRoute('resultat', [
                        'id' => $utilisateur->getId(),
                        'utilisateur' => $utilisateur
                    ]);

                } else {
                    // ENVOIE MAIL NON
                    $calculateur->calculerAide($utilisateur, $entityManager, $mailerService);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();
                    $this->addFlash('success', 'Simulation réalisée avec succès');
                    return $this->redirectToRoute('resultat', [
                        'id' => $utilisateur->getId()
                    ]);
                }
            } else {
                //n accepte pas le traitement des données
                $this->addFlash('message', 'veuillez accepter l\'utilisation de vos données');
                return $this->render('create.html.twig', [
                    'utilisateurForm' => $utilisateurForm->createView(),
                ]);
            }
        } else {

            $this->addFlash('error', 'Veuillez corriger les erreurs');
            return $this->render('create.html.twig', [
                'utilisateurForm' => $utilisateurForm->createView(),
            ]);
        }
    }



    private $em;

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


        $current = $this->getUser();

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
}