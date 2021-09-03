<?php

namespace App\Controller;

use App\Entity\Resultat;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\Calculateur;
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
        Calculateur $calculateur
    ): Response
    {
        $utilisateur = new Utilisateur();

        $utilisateur->setDateSimulation(new \DateTime('now'));

        $utilisateurForm = $this->createForm(UtilisateurType::class, $utilisateur);



        $utilisateurForm->handleRequest($request);
        $calculateur->calculerAide($utilisateur, $entityManager);

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès');

        }
        return $this->render('create.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
        ]);
    }

    /**
     * @Route("/resultat/{id}", name="resultat")
     */
    public function resultat(
        Request $request,
        EntityManagerInterface $entityManager,
        Calculateur $calculateur
    ): Response
    {

        return $this->render('/resultat.html.twig', [
            'resultat' => $resultat->createView(),
        ]);
    }

}