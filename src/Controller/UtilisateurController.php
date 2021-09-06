<?php

namespace App\Controller;

use App\Entity\Resultat;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\Calculateur;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
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
        $utilisateur->setDateSimulation(new \DateTime('now'));

        $utilisateurForm->handleRequest($request);


        $agree = $utilisateur->getAgreeTerms();
        //var_dump($agree);
        $agreeEmail = $utilisateur->getAgreeEmail();
        //var_dump(sizeof($agreeEmail));

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            if ( sizeof($agree) == 1) {

                if ( sizeof($agreeEmail) == 1) {
                    // ENVOIE MAIL OK
                    $calculateur->calculerAide($utilisateur, $entityManager, $mailerService);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();



                    $this->addFlash('success', 'Simulation réalisée avec succès');
                    $this->addFlash('success', 'Mail envoyé');

                    return $this->redirectToRoute('resultat', [
                        'id' => $utilisateur->getId()
                    ]);
                    #TODO FAIRE ENVOIE DE MAIL

                } else {
                    $calculateur->calculerAide($utilisateur, $entityManager, $mailerService);
                    #TODO ENVOIE MAIL NON
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();
                    var_dump($utilisateur);
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
        }
        return $this->render('create.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
        ]);
    }

    /**
     * @Route("/resultat/{id}", name="resultat", methods={"GET"})
     */
    public function resultat(Utilisateur $utilisateur, MailerService $mailerService): Response
    {

        $agreeT = $utilisateur->getAgreeTerms();
        dd($agreeT);

        $email = $utilisateur->getEmail();

        $mailerService->send(
            "Votre simulation",
            "contact@top-enr.com",
            $email,
            "email/contact.html.twig",
            [
                "name" => $utilisateur->getNom(),
                "total" => $utilisateur->getResultat()->getMontantTotal(),
            ]

        );

        return $this->render('resultat.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

}