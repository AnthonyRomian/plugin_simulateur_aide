<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\Calculateur;
use App\Service\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


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

        // definition de la date de simulation de l utilisateur
        $utilisateur->setDateSimulation(new DateTime('now'));

        $utilisateurForm->handleRequest($request);
        $agree = $utilisateur->getAgreeTerms();

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {

            if (sizeof($agree) == 1) {
                // l utlisateur accepte le traitement de ses données

                // Envoie vers service calculateur qui calcule les droits aux aides
                try {
                    $calculateur->calculerAide($utilisateur, $entityManager, $mailerService);
                } catch (TransportExceptionInterface | RuntimeError | LoaderError | SyntaxError $e) {
                    return $this->render('utilisateur/create.html.twig', [
                        'utilisateurForm' => $utilisateurForm->createView(),
                    ]);
                }

                // le rappel est automatiquement mit a false l'utilisateur n'a pas été rappélé apres 1 mois
                $utilisateur->setRappel(false);


                $entityManager = $this->getDoctrine()->getManager();

                // Formattage de nom et prenom
                $utilisateurForm->getData()->setNom(strtoupper($utilisateurForm->getData()->getNom()));
                $utilisateurForm->getData()->setPrenom(ucfirst(strtolower($utilisateurForm->getData()->getPrenom())));

                // persist des données utilisateurs
                $entityManager->persist($utilisateur);
                $entityManager->flush();
                $this->addFlash('success', 'Simulation réalisée avec succès');

                // redirection vers la page résultat
                return $this->redirectToRoute('resultat', [
                    'id' => $utilisateur->getId(),
                    'utilisateur' => $utilisateur
                ]);

            } else {
                // l utilisateur n accepte pas le traitement des données
                $this->addFlash('message', 'veuillez accepter l\'utilisation de vos données');
                return $this->render('utilisateur/create.html.twig', [
                    'utilisateurForm' => $utilisateurForm->createView(),
                ]);
            }
        } else {

            $this->addFlash('error', 'Veuillez corriger les erreurs');
            return $this->render('utilisateur/create.html.twig', [
                'utilisateurForm' => $utilisateurForm->createView(),
            ]);
        }
    }
}