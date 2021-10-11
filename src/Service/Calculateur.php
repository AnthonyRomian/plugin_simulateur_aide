<?php


namespace App\Service;

use App\Entity\Resultat;
use App\Entity\Utilisateur;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Calculateur extends AbstractController
{

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function calculerAide(Utilisateur $utilisateur, EntityManagerInterface $entityManager, MailerService $mailerService): Resultat
    {
        /*$simulHeure = $utilisateur->getDateSimulation();
        $simulHeureObj = $simulHeure->format('Y-m-d H:i:s');*/
        //-------------PRIME RENOV SEUL---------//
        //---------CESI------//
        $renov_cesi_Bleue = 4000;
        $renov_cesi_Jaune = 3000;
        $renov_cesi_Violet = 2000;
        $renov_cesi_Rose = 0;
        $renov_non = 0;

        //---------S Sanitaire et Chauffage------//
        $renov_ssc_Bleue = 10000;
        $renov_ssc_Jaune = 8000;
        $renov_ssc_Violet = 4000;
        $renov_ssc_Rose = 0;

        //-------------PRIME RENOV electricité ---------//
        //---------ELEC------//
        $renov_elec_Bleue = 2500;
        $renov_elec_Jaune = 2000;
        $renov_elec_Violet = 1000;
        $renov_elec_Rose = 0;

        //-------------CEE ELEC-----------------//
        //---------ELEC------//
        $cee_elec_Bleue = 251;
        $cee_elec_Jaune = 125;
        $cee_elec_Violet = 125;
        $cee_elec_Rose = 125;

        //-------------CEE-----------------//
        //---------CESI------//
        $cee_cesi_Bleue = 275;
        $cee_cesi_Jaune = 137;
        $cee_cesi_Violet = 137;
        $cee_cesi_Rose = 137;
        $cee_non = 0;

        //---------S Sanitaire et Chauffage------//
        $cee_ssc_Bleue = 1400;
        $cee_ssc_Jaune = 1400;
        $cee_ssc_Violet = 650;
        $cee_ssc_Rose = 650;

        //------Coup de pouce fioul--------//
        $pouce_Bleue = 4000;
        $pouce_Jaune = 4000;
        $pouce_Violet = 2500;
        $pouce_Rose = 2500;
        $pouce_Non = 0;

        $nbre_pers_foy = $utilisateur->getNbrePersFoyer();
        $rfi = $utilisateur->getRevenuFiscal();
        $cp = $utilisateur->getCodePostal();
        $anciennete = $utilisateur->getAncienneteEligible();
        $produitVise = $utilisateur->getProduitVise();
        $energie = $utilisateur->getEnergie();
        $dep = substr($cp, -5, 2);
        $agreeEmail = $utilisateur->getAgreeEmail();

        // si l utilisateur n'a pas de resultat
        if ( $utilisateur->getResultat() == null ) {
            // nouvelle instance de resultat
            $resultat = new Resultat();
            //attribution du resultat a l'utilisateur
            $resultat->setUtilisateurSimulation($utilisateur);
        } else {
            // si il en a déja un on recupere le resultat de l utilisateur
            $resultat = $utilisateur->getResultat();
        }


        if ($anciennete == true)
        {
            //il est elligible aux aides

            # ile de france
            # $typedeplafond_nbreFoyer_palier
            $plafond_prime_renov_1_1 = 20593;
            $plafond_prime_renov_1_2 = 25068;
            $plafond_prime_renov_1_3 = 38184;

            $plafond_prime_renov_2_1 = 30225;
            $plafond_prime_renov_2_2 = 36792;
            $plafond_prime_renov_2_3 = 56130;

            $plafond_prime_renov_3_1 = 36297;
            $plafond_prime_renov_3_2 = 44188;
            $plafond_prime_renov_3_3 = 67585;

            $plafond_prime_renov_4_1 = 42381;
            $plafond_prime_renov_4_2 = 51597;
            $plafond_prime_renov_4_3 = 79041;

            # $typedeplafond_nbreFoyer_palier
            $plafond_prime_renov_5_1 = 48488;
            $plafond_prime_renov_5_2 = 59026;
            $plafond_prime_renov_5_3 = 90496;

            $plafond_prime_renov_6_1 = $plafond_prime_renov_5_1 + (($nbre_pers_foy - 5) * 6096);
            $plafond_prime_renov_6_2 = $plafond_prime_renov_5_2 + (($nbre_pers_foy - 5) * 7422);
            $plafond_prime_renov_6_3 = $plafond_prime_renov_5_3 + (($nbre_pers_foy - 5) * 11455);

            # HORS ile de france
            $plafond_prime_renov_province_1_1 = 14879;
            $plafond_prime_renov_province_1_2 = 19074;
            $plafond_prime_renov_province_1_3 = 29148;

            $plafond_prime_renov_province_2_1 = 21760;
            $plafond_prime_renov_province_2_2 = 27896;
            $plafond_prime_renov_province_2_3 = 42848;

            $plafond_prime_renov_province_3_1 = 26170;
            $plafond_prime_renov_province_3_2 = 33547;
            $plafond_prime_renov_province_3_3 = 51592;

            $plafond_prime_renov_province_4_1 = 30572;
            $plafond_prime_renov_province_4_2 = 39192;
            $plafond_prime_renov_province_4_3 = 60336;

            $plafond_prime_renov_province_5_1 = 34993;
            $plafond_prime_renov_province_5_2 = 44860;
            $plafond_prime_renov_province_5_3 = 69081;

            $plafond_prime_renov_province_6_1 = $plafond_prime_renov_province_5_1 + (($nbre_pers_foy - 5) * 4412);
            $plafond_prime_renov_province_6_2 = $plafond_prime_renov_province_5_2 + (($nbre_pers_foy - 5) * 5651);
            $plafond_prime_renov_province_6_3 = $plafond_prime_renov_province_5_3 + (($nbre_pers_foy - 5) * 8744);


            # ZONE BLEUE
            if ($rfi > 0 && $rfi < $plafond_prime_renov_1_1 && $nbre_pers_foy == 1 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_2_1 && $nbre_pers_foy == 2 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_3_1 && $nbre_pers_foy == 3 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_4_1 && $nbre_pers_foy == 4 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_5_1 && $nbre_pers_foy == 5 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_6_1 && $nbre_pers_foy >= 6 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||

                $rfi > 0 && $rfi < $plafond_prime_renov_province_1_1 && $nbre_pers_foy == 1 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_province_2_1 && $nbre_pers_foy == 2 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_province_3_1 && $nbre_pers_foy == 3 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_province_4_1 && $nbre_pers_foy == 4 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_province_5_1 && $nbre_pers_foy == 5 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > 0 && $rfi < $plafond_prime_renov_province_6_1 && $nbre_pers_foy >= 6 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95))
            {
                if ($produitVise == 'Eau chaude sanitaire') {
                    $resultat->setPrimeRenov($renov_cesi_Bleue);
                    $resultat->setCee($cee_cesi_Bleue);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_cesi_Bleue + $cee_cesi_Bleue + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et électricité') {
                    $resultat->setPrimeRenov($renov_elec_Bleue);
                    $resultat->setCee($cee_elec_Bleue);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_elec_Bleue + $cee_elec_Bleue + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Bleue);
                    $resultat->setCee($cee_ssc_Bleue);
                    $resultat->setCdpChauffage($pouce_Bleue);
                    $resultat->setMontantTotal($renov_ssc_Bleue + $cee_ssc_Bleue + $pouce_Bleue);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Bleue);
                    $resultat->setCee($cee_ssc_Bleue);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_ssc_Bleue + $cee_ssc_Bleue + $pouce_Non);
                }
                $entityManager->persist($resultat);
                $entityManager->flush();

                //MAIL OK
                if ($agreeEmail  == 1) {
                    $email = $utilisateur->getEmail();
                    $mailerService->send("Votre simulation", "contact@top-enr.com", $email, "email/contact.html.twig",
                        [
                            // ajouter tout les infos resultats
                            "name" => $resultat->getUtilisateurSimulation()->getNom(),
                            "prenom" => $resultat->getUtilisateurSimulation()->getPrenom(),
                            "prime_renov" => $resultat->getPrimeRenov(),
                            "prime_cee" => $resultat->getCee(),
                            "prime_fioul" => $resultat->getCdpChauffage(),
                            "total" => $resultat->getMontantTotal(),
                            "proprietee"=> $resultat->getUtilisateurSimulation()->getProprietaire(),
                        ]
                    );
                    $this->addFlash('success', 'Mail envoyé');
                }
                return $resultat;

            # ZONE JAUNE
            } elseif ($rfi > $plafond_prime_renov_1_1 && $rfi < $plafond_prime_renov_1_2 && $nbre_pers_foy == 1 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_2_1 && $rfi < $plafond_prime_renov_2_2 && $nbre_pers_foy == 2 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_3_1 && $rfi < $plafond_prime_renov_3_2 && $nbre_pers_foy == 3 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_4_1 && $rfi < $plafond_prime_renov_4_2 && $nbre_pers_foy == 4 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_5_1 && $rfi < $plafond_prime_renov_5_2 && $nbre_pers_foy == 5 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_6_1 && $rfi < $plafond_prime_renov_6_2 && $nbre_pers_foy >= 6 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||

                $rfi > $plafond_prime_renov_province_1_1 && $rfi < $plafond_prime_renov_province_1_2 && $nbre_pers_foy == 1 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_2_1 && $rfi < $plafond_prime_renov_province_2_2 && $nbre_pers_foy == 2 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_3_1 && $rfi < $plafond_prime_renov_province_3_2 && $nbre_pers_foy == 3 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_4_1 && $rfi < $plafond_prime_renov_province_4_2 && $nbre_pers_foy == 4 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_5_1 && $rfi < $plafond_prime_renov_province_5_2 && $nbre_pers_foy == 5 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_6_1 && $rfi < $plafond_prime_renov_province_6_2 && $nbre_pers_foy >= 6 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95)) {
                if ($produitVise == 'Eau chaude sanitaire') {
                    $resultat->setPrimeRenov($renov_cesi_Jaune);
                    $resultat->setCee($cee_cesi_Jaune);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_cesi_Jaune + $cee_cesi_Jaune + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et électricité') {
                    $resultat->setPrimeRenov($renov_elec_Jaune);
                    $resultat->setCee($cee_elec_Jaune);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_elec_Jaune + $cee_elec_Jaune + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Jaune);
                    $resultat->setCee($cee_ssc_Jaune);
                    $resultat->setCdpChauffage($pouce_Jaune);
                    $resultat->setMontantTotal($renov_ssc_Jaune + $cee_ssc_Jaune + $pouce_Jaune);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Jaune);
                    $resultat->setCee($cee_ssc_Jaune);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_ssc_Jaune + $cee_ssc_Jaune + $pouce_Non);
                }
                $entityManager->persist($resultat);
                $entityManager->flush();

                /*if ($agreeEmail == 1) {

                    $email = $utilisateur->getEmail();

                    $mailerService->send("Votre simulation", "contact@top-enr.com", $email, "email/contact.html.twig",
                        [
                            // ajouter tout les infos resultats
                            "name" => $utilisateur->getNom(),
                            "prenom" => $resultat->getUtilisateurSimulation()->getPrenom(),
                            "prime_renov" => $utilisateur->getResultat()->getPrimeRenov(),
                            "prime_cee" => $utilisateur->getResultat()->getCee(),
                            "prime_fioul" => $utilisateur->getResultat()->getCdpChauffage(),
                            "total" => $utilisateur->getResultat()->getMontantTotal(),
                        ]
                    );
                    $this->addFlash('success', 'Mail envoyé');
                }*/
                return $resultat;

            # ZONE VIOLET
            } elseif ($rfi > $plafond_prime_renov_1_2 && $rfi < $plafond_prime_renov_1_3 && $nbre_pers_foy == 1 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_2_2 && $rfi < $plafond_prime_renov_2_3 && $nbre_pers_foy == 2 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_3_2 && $rfi < $plafond_prime_renov_3_3 && $nbre_pers_foy == 3 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_4_2 && $rfi < $plafond_prime_renov_4_3 && $nbre_pers_foy == 4 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_5_2 && $rfi < $plafond_prime_renov_5_3 && $nbre_pers_foy == 5 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_6_2 && $rfi < $plafond_prime_renov_6_3 && $nbre_pers_foy >= 6 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||

                $rfi > $plafond_prime_renov_province_1_2 && $rfi < $plafond_prime_renov_province_1_3 && $nbre_pers_foy == 1 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_2_2 && $rfi < $plafond_prime_renov_province_2_3 && $nbre_pers_foy == 2 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_3_2 && $rfi < $plafond_prime_renov_province_3_3 && $nbre_pers_foy == 3 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_4_2 && $rfi < $plafond_prime_renov_province_4_3 && $nbre_pers_foy == 4 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_5_2 && $rfi < $plafond_prime_renov_province_5_3 && $nbre_pers_foy == 5 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_6_2 && $rfi < $plafond_prime_renov_province_6_3 && $nbre_pers_foy >= 6 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95)) {
                if ($produitVise == 'Eau chaude sanitaire') {
                    $resultat->setPrimeRenov($renov_cesi_Violet);
                    $resultat->setCee($cee_cesi_Violet);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_cesi_Violet + $cee_cesi_Violet + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et électricité') {
                    $resultat->setPrimeRenov($renov_elec_Violet);
                    $resultat->setCee($cee_elec_Violet);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_elec_Violet + $cee_elec_Violet + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Violet);
                    $resultat->setCee($cee_ssc_Violet);
                    $resultat->setCdpChauffage($pouce_Violet);
                    $resultat->setMontantTotal($renov_ssc_Violet + $cee_ssc_Violet + $pouce_Violet);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Violet);
                    $resultat->setCee($cee_ssc_Violet);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_ssc_Violet + $cee_ssc_Violet + $pouce_Non);
                }
                $entityManager->persist($resultat);
                $entityManager->flush();

                /*if ($agreeEmail == 1) {

                    $email = $utilisateur->getEmail();

                    $mailerService->send("Votre simulation", "contact@top-enr.com", $email, "email/contact.html.twig",
                        [
                            // ajouter tout les infos resultats
                            "name" => $utilisateur->getNom(),
                            "prenom" => $resultat->getUtilisateurSimulation()->getPrenom(),
                            "prime_renov" => $utilisateur->getResultat()->getPrimeRenov(),
                            "prime_cee" => $utilisateur->getResultat()->getCee(),
                            "prime_fioul" => $utilisateur->getResultat()->getCdpChauffage(),
                            "total" => $utilisateur->getResultat()->getMontantTotal(),
                        ]
                    );
                    $this->addFlash('success', 'Mail envoyé');
                }*/
                return $resultat;

            # ZONE ROSE
            } elseif ($rfi > $plafond_prime_renov_1_3 && $nbre_pers_foy == 1 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_2_3 && $nbre_pers_foy == 2 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_3_3 && $nbre_pers_foy == 3 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_4_3 && $nbre_pers_foy == 4 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_5_3 && $nbre_pers_foy == 5 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||
                $rfi > $plafond_prime_renov_6_3 && $nbre_pers_foy >= 6 && ($dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95) ||

                $rfi > $plafond_prime_renov_province_1_3 && $nbre_pers_foy == 1 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_2_3 && $nbre_pers_foy == 2 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_3_3 && $nbre_pers_foy == 3 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_4_3 && $nbre_pers_foy == 4 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_5_3 && $nbre_pers_foy == 5 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95) ||
                $rfi > $plafond_prime_renov_province_6_3 && $nbre_pers_foy >= 6 && ($dep != 75 && $dep != 77 && $dep != 78 && $dep != 91 && $dep != 92 && $dep != 93 && $dep != 94 && $dep != 95)) {
                if ($produitVise == 'Eau chaude sanitaire') {
                    $resultat->setPrimeRenov($renov_cesi_Rose);
                    $resultat->setCee($cee_cesi_Rose);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_cesi_Rose + $cee_cesi_Rose + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et électricité') {
                    $resultat->setPrimeRenov($renov_elec_Rose);
                    $resultat->setCee($cee_elec_Rose);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_elec_Rose + $cee_elec_Rose + $pouce_Non);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Rose);
                    $resultat->setCee($cee_ssc_Rose);
                    $resultat->setCdpChauffage($pouce_Rose);
                    $resultat->setMontantTotal($renov_ssc_Rose + $cee_ssc_Rose + $pouce_Rose);
                } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                    $resultat->setPrimeRenov($renov_ssc_Rose);
                    $resultat->setCee($cee_ssc_Rose);
                    $resultat->setCdpChauffage($pouce_Non);
                    $resultat->setMontantTotal($renov_ssc_Rose + $cee_ssc_Rose + $pouce_Non);
                }

                $entityManager->persist($resultat);
                $entityManager->flush();

                /*if ($agreeEmail == 1) {

                    $email = $utilisateur->getEmail();

                    $mailerService->send("Votre simulation", "contact@top-enr.com", $email, "email/contact.html.twig",
                        [
                            // ajouter tout les infos resultats
                            "name" => $utilisateur->getNom(),
                            "prenom" => $resultat->getUtilisateurSimulation()->getPrenom(),
                            "prime_renov" => $utilisateur->getResultat()->getPrimeRenov(),
                            "prime_cee" => $utilisateur->getResultat()->getCee(),
                            "prime_fioul" => $utilisateur->getResultat()->getCdpChauffage(),
                            "total" => $utilisateur->getResultat()->getMontantTotal(),
                        ]
                    );
                    $this->addFlash('success', 'Mail envoyé');
                }*/
                return $resultat;
            }

        }
        else
        {
            $resultat->setUtilisateurSimulation($utilisateur);

            //Definition des primes a ZERO
            $resultat->setPrimeRenov($renov_non);
            $resultat->setCee($cee_non);
            $resultat->setCdpChauffage($pouce_Non);
            $resultat->setMontantTotal($renov_non + $cee_non + $pouce_Non);
            $entityManager->persist($resultat);
            $entityManager->flush();


            if ($agreeEmail== 1) {
                $email = $utilisateur->getEmail();
                $mailerService->send("Votre simulation", "contact@top-enr.com", $email, "email/contact.html.twig",
                    [
                        // ajouter tout les infos resultats
                        "name" => $utilisateur->getNom(),
                        "prenom" => $resultat->getUtilisateurSimulation()->getPrenom(),
                        "prime_renov" => $utilisateur->getResultat()->getPrimeRenov(),
                        "prime_cee" => $utilisateur->getResultat()->getCee(),
                        "prime_fioul" => $utilisateur->getResultat()->getCdpChauffage(),
                        "total" => $utilisateur->getResultat()->getMontantTotal(),
                    ]
                );
                $this->addFlash('success', 'Mail envoyé');
            }
            return $resultat;
        }
        return $resultat;
    }
}




