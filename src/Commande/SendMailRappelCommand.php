<?php


namespace App\Commande;


use App\Repository\UtilisateurRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SendMailRappelCommand extends Command
{
    private $utilisateurRepository;
    private $entityManager;
    private $mailerService;
    protected static $defaultName = 'app:send-resultat';

    public function __construct(UtilisateurRepository $utilisateurRepository,
                                MailerService $mailerService,
                                EntityManagerInterface $entityManager)
    {
        $this->utilisateurRepository = $utilisateurRepository;
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
            $dateSimulationRappel = $dateSimulation+2628000;
            $dateDuJour = time();
            //  comparer avec date de simulation + X mois.
            // si date actuelle superieure ou egale a date de simul + 30 jours alors envoyé mail de rappel
            if ( $dateDuJour >= $dateSimulationRappel && $utilisateur->getRappel() == 0 && $utilisateur->getAgreeEmail() == true ){
                $email = $utilisateur->getEmail();
                $utilisateur->setRappel(true);
                try {
                    $this->mailerService->send("Rappel - Votre simulation", "contact@top-enr.com", $email, "email/contact-rappel.html.twig",
                        [
                            // ajouter tous les infos resultats
                            "name" => $utilisateur->getNom(),
                            "prime_renov" => $utilisateur->getResultat()->getPrimeRenov(),
                            "prime_cee" => $utilisateur->getResultat()->getCee(),
                            "prime_fioul" => $utilisateur->getResultat()->getCdpChauffage(),
                            "total" => $utilisateur->getResultat()->getMontantTotal(),
                            "proprietee" => $utilisateur->getProprietaire(),
                        ]
                    );
                } catch (TransportExceptionInterface |LoaderError |RuntimeError |SyntaxError $e) {
                    // email non envoyé erreur
                }
                $this->entityManager->persist($utilisateur);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}