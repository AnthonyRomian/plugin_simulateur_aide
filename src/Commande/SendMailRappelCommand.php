<?php


namespace App\Commande;


use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class SendMailRappelCommand extends Command
{
    private $utilisateurRepository;
    private $mailer;
    private $entityManager;
    private $mailerService;
    protected static $defaultName = 'app:send-resultat';

    public function __construct(UtilisateurRepository $utilisateurRepository,
                                MailerInterface $mailer,
                                MailerService $mailerService,
                                EntityManagerInterface $entityManager)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $utilisateurs = $this->utilisateurRepository->findAll();


        for ( $i = 0; $i < sizeof($utilisateurs) ; $i++){
            $utilisateur = $utilisateurs[$i];

            // recuperer la date de simulation
            $dateSimulation = $utilisateur->getDateSimulation()->getTimestamp();
            dump($dateSimulation);
            $dateSimulationRappel = $dateSimulation+2628000;
            dump($dateSimulationRappel);
            $dateDuJour = time();
            dump($dateDuJour);
            //  comparer avec date de simulation + X mois.
            // si date actuelle superieure ou egale a date de simul + 30 jours alors envoyé mail de rappel
            if ( $dateDuJour >= $dateSimulationRappel && $utilisateur->getRappel() == 0 ){
                $email = $utilisateur->getEmail();

                $utilisateur->setRappel(true);
                $this->mailerService->send("Rappel - Votre simulation", "contact@top-enr.com", $email, "email/contact.html.twig",
                    [
                        // ajouter tous les infos resultats
                        "name" => $utilisateur->getNom(),
                        "prime_renov" => $utilisateur->getResultat()->getPrimeRenov(),
                        "prime_cee" => $utilisateur->getResultat()->getCee(),
                        "prime_fioul" => $utilisateur->getResultat()->getCdpChauffage(),
                        "total" => $utilisateur->getResultat()->getMontantTotal(),
                        "proprietee"=> $utilisateur->getProprietaire(),
                    ]
                );

                $this->entityManager->persist($utilisateur);
                $this->entityManager->flush();


            }

        }
        return Command::SUCCESS;
    }


}