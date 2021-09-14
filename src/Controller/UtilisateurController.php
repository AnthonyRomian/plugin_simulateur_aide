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
                    $utilisateurForm->getData()->setNom(strtoupper($utilisateurForm->getData()->getNom()));
                    $utilisateurForm->getData()->setPrenom(ucfirst(strtolower($utilisateurForm->getData()->getPrenom())));

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
                    $utilisateurForm->getData()->setNom(strtoupper($utilisateurForm->getData()->getNom()));
                    $utilisateurForm->getData()->setPrenom(ucfirst(strtolower($utilisateurForm->getData()->getPrenom())));

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
}