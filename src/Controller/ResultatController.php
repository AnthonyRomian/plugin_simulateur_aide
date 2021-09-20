<?php


namespace App\Controller;


use App\Entity\Utilisateur;
use App\Service\MailerService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class ResultatController extends AbstractController
{

    /**
     * @Route("/resultat/{id}", name="resultat", methods={"GET"})
     */
    public function resultat(Utilisateur $utilisateur, MailerService $mailerService): Response
    {


        return $this->render('utilisateur/resultat.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }




}