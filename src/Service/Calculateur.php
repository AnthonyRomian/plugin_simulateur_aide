<?php


namespace App\Service;

use App\Entity\Resultat;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Calculateur extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculerAide(Utilisateur $utilisateur, EntityManagerInterface $entityManager)
    {

        $nbre_pers_foy = $utilisateur->getNbrePersFoyer();
        $rfi = $utilisateur->getRevenuFiscal();
        $cp = $utilisateur->getCodePostal();
        $anciennete = $utilisateur->getAncienneteEligible();
        $proprietaireOk = $utilisateur->getProprietaire();
        $produitVise = $utilisateur->getProduitVise();
        $energie = $utilisateur->getEnergie();

        //$simulHeure = $utilisateur->getDateSimulation();
        //$simulHeureObj = $simulHeure->format('Y-m-d H:i:s');

        //-------------PRIME RENOV---------//
        //---------CESI------//
        $renov_cesi_Jaune = 4000;
        $renov_cesi_Gris = 3000;
        $renov_cesi_Rouge = 2000;
        $renov_cesi_Bleu = 0;
        $renov_non = 0;

        //---------SSC------//
        $renov_ssc_Jaune = 10000;
        $renov_ssc_Gris = 8000;
        $renov_ssc_Rouge = 4000;
        $renov_ssc_Bleu = 0;


        //-------------CEE-----------------//
        //---------CESI------//
        $cee_cesi_Jaune = 275;
        $cee_cesi_Gris = 137;
        $cee_cesi_Rouge = 137;
        $cee_cesi_Bleu = 137;
        $cee_non = 0;

        //---------SSC------//
        $cee_ssc_Jaune = 1400;
        $cee_ssc_Gris = 1400;
        $cee_ssc_Rouge = 650;
        $cee_ssc_Bleu = 650;

        //------Coup de pouce fioul--------//
        $pouce_Jaune = 4000;
        $pouce_Gris = 4000;
        $pouce_Rouge = 2500;
        $pouce_Bleu = 2500;
        $pouce_Non = 0;

        $dep = substr($cp, -5, 2);
        $resultat = new Resultat();

        $resultat->setUtilisateurSimulation($utilisateur);
        //var_dump($this->entityManager->getRepository(Utilisateur::class)->findByExampleField($utilisateur->getDateSimulation()));

        // ($anciennete == true && $proprietaireOk == true)
        if ($anciennete == true ) {
            //il est elligible aux aides
            //si code postal ile de france
            if ( $dep == 75 || $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95 ) {

                # ile de france
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

                $plafond_prime_renov_5_1 = 42381;
                $plafond_prime_renov_5_2 = 51597;
                $plafond_prime_renov_5_3 = 79041;

                $plafond_prime_renov_6_1 = 34993+(($nbre_pers_foy-5)*6096);
                $plafond_prime_renov_6_2 = 44860+(($nbre_pers_foy-5)*7422);
                $plafond_prime_renov_6_3 = 69081+(($nbre_pers_foy-5)*11455);

                if ($rfi > 0 && $rfi < $plafond_prime_renov_1_1 && $nbre_pers_foy == 1 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_2_1 && $nbre_pers_foy == 2 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_3_1 && $nbre_pers_foy == 3 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_4_1 && $nbre_pers_foy == 4 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_5_1 && $nbre_pers_foy == 5 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_6_1 && $nbre_pers_foy >= 6 ) {

                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Jaune);
                        $resultat->setCee($cee_cesi_Jaune);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Jaune + $cee_cesi_Jaune + $pouce_Non);

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
                    return $resultat;

                } elseif ($rfi > $plafond_prime_renov_1_1 && $rfi < $plafond_prime_renov_1_2 && $nbre_pers_foy == 1 ||
                    $rfi > $plafond_prime_renov_2_1 && $rfi < $plafond_prime_renov_2_2 && $nbre_pers_foy == 2 ||
                    $rfi > $plafond_prime_renov_3_1 && $rfi < $plafond_prime_renov_3_2 && $nbre_pers_foy == 3 ||
                    $rfi > $plafond_prime_renov_4_1 && $rfi < $plafond_prime_renov_4_2 && $nbre_pers_foy == 4 ||
                    $rfi > $plafond_prime_renov_5_1 && $rfi < $plafond_prime_renov_5_2 && $nbre_pers_foy == 5 ||
                    $rfi > $plafond_prime_renov_6_1 && $rfi < $plafond_prime_renov_6_2 && $nbre_pers_foy >= 6) {
                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Gris);
                        $resultat->setCee($cee_cesi_Gris);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Gris + $cee_cesi_Gris + $pouce_Non);

                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Gris);
                        $resultat->setCee($cee_ssc_Gris);
                        $resultat->setCdpChauffage($pouce_Gris);
                        $resultat->setMontantTotal($renov_ssc_Gris + $cee_ssc_Gris + $pouce_Gris);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Gris);
                        $resultat->setCee($cee_ssc_Gris);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_ssc_Gris + $cee_ssc_Gris + $pouce_Non);
                    }
                    $entityManager->persist($resultat);
                    $entityManager->flush();
                    return $resultat;
                } elseif ($rfi > $plafond_prime_renov_1_2 && $rfi < $plafond_prime_renov_1_3 && $nbre_pers_foy == 1 ||
                    $rfi > $plafond_prime_renov_2_2 && $rfi < $plafond_prime_renov_2_3 && $nbre_pers_foy == 2 ||
                    $rfi > $plafond_prime_renov_3_2 && $rfi < $plafond_prime_renov_3_3 && $nbre_pers_foy == 3 ||
                    $rfi > $plafond_prime_renov_4_2 && $rfi < $plafond_prime_renov_4_3 && $nbre_pers_foy == 4 ||
                    $rfi > $plafond_prime_renov_5_2 && $rfi < $plafond_prime_renov_5_3 && $nbre_pers_foy == 5 ||
                    $rfi > $plafond_prime_renov_6_2 && $rfi < $plafond_prime_renov_6_3 && $nbre_pers_foy >= 6) {
                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Rouge);
                        $resultat->setCee($cee_cesi_Rouge);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Rouge + $cee_cesi_Rouge + $pouce_Non);

                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Rouge);
                        $resultat->setCee($cee_ssc_Rouge);
                        $resultat->setCdpChauffage($pouce_Rouge);
                        $resultat->setMontantTotal($renov_ssc_Rouge + $cee_ssc_Rouge + $pouce_Rouge);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Rouge);
                        $resultat->setCee($cee_ssc_Rouge);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_ssc_Rouge + $cee_ssc_Rouge + $pouce_Non);
                    }
                    $entityManager->persist($resultat);
                    $entityManager->flush();
                    return $resultat;
                } elseif ($rfi > $plafond_prime_renov_1_3 && $nbre_pers_foy == 1 ||
                    $rfi > $plafond_prime_renov_2_3 && $nbre_pers_foy == 2 ||
                    $rfi > $plafond_prime_renov_3_3 && $nbre_pers_foy == 3 ||
                    $rfi > $plafond_prime_renov_4_3 && $nbre_pers_foy == 4 ||
                    $rfi > $plafond_prime_renov_5_3 && $nbre_pers_foy == 5 ||
                    $rfi > $plafond_prime_renov_6_3 && $nbre_pers_foy >= 6) {
                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Bleu);
                        $resultat->setCee($cee_cesi_Bleu);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Bleu + $cee_cesi_Bleu + $pouce_Non);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Bleu);
                        $resultat->setCee($cee_ssc_Bleu);
                        $resultat->setCdpChauffage($pouce_Bleu);
                        $resultat->setMontantTotal($renov_ssc_Bleu + $cee_ssc_Bleu + $pouce_Bleu);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Bleu);
                        $resultat->setCee($cee_ssc_Bleu);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_ssc_Bleu + $cee_ssc_Bleu + $pouce_Non);
                    }

                    $entityManager->persist($resultat);
                    $entityManager->flush();
                    return $resultat;
                }
            } else {

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

                $plafond_prime_renov_province_6_1 = 34993+(($nbre_pers_foy-5)*4412);
                $plafond_prime_renov_province_6_2 = 44860+(($nbre_pers_foy-5)*5651);
                $plafond_prime_renov_province_6_3 = 69081+(($nbre_pers_foy-5)*8744);

                if ($rfi > 0 && $rfi < $plafond_prime_renov_province_1_1 && $nbre_pers_foy == 1 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_province_2_1 && $nbre_pers_foy == 2 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_province_3_1 && $nbre_pers_foy == 3 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_province_4_1 && $nbre_pers_foy == 4 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_province_5_1 && $nbre_pers_foy == 5 ||
                    $rfi > 0 && $rfi < $plafond_prime_renov_province_6_1 && $nbre_pers_foy >= 6) {

                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Jaune);
                        $resultat->setCee($cee_cesi_Jaune);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Jaune + $cee_cesi_Jaune + $pouce_Non);

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
                    return $resultat;

                } elseif ($rfi > $plafond_prime_renov_province_1_1 && $rfi < $plafond_prime_renov_province_1_2 && $nbre_pers_foy == 1 ||
                    $rfi > $plafond_prime_renov_province_2_1 && $rfi < $plafond_prime_renov_province_2_2 && $nbre_pers_foy == 2 ||
                    $rfi > $plafond_prime_renov_province_3_1 && $rfi < $plafond_prime_renov_province_3_2 && $nbre_pers_foy == 3 ||
                    $rfi > $plafond_prime_renov_province_4_1 && $rfi < $plafond_prime_renov_province_4_2 && $nbre_pers_foy == 4 ||
                    $rfi > $plafond_prime_renov_province_5_1 && $rfi < $plafond_prime_renov_province_5_2 && $nbre_pers_foy == 5 ||
                    $rfi > $plafond_prime_renov_province_6_1 && $rfi < $plafond_prime_renov_province_6_2 && $nbre_pers_foy >= 6 ) {
                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Gris);
                        $resultat->setCee($cee_cesi_Gris);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Gris + $cee_cesi_Gris + $pouce_Non);

                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Gris);
                        $resultat->setCee($cee_ssc_Gris);
                        $resultat->setCdpChauffage($pouce_Bleu);
                        $resultat->setMontantTotal($renov_ssc_Gris + $cee_ssc_Gris + $pouce_Bleu);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Gris);
                        $resultat->setCee($cee_ssc_Gris);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_ssc_Gris + $cee_ssc_Gris + $pouce_Non);
                    }
                    $entityManager->persist($resultat);
                    $entityManager->flush();
                    return $resultat;
                } elseif ($rfi > $plafond_prime_renov_province_1_2 && $rfi < $plafond_prime_renov_province_1_3 && $nbre_pers_foy == 1 ||
                    $rfi > $plafond_prime_renov_province_2_2 && $rfi < $plafond_prime_renov_province_2_3 && $nbre_pers_foy == 2 ||
                    $rfi > $plafond_prime_renov_province_3_2 && $rfi < $plafond_prime_renov_province_3_3 && $nbre_pers_foy == 3 ||
                    $rfi > $plafond_prime_renov_province_4_2 && $rfi < $plafond_prime_renov_province_4_3 && $nbre_pers_foy == 4 ||
                    $rfi > $plafond_prime_renov_province_5_2 && $rfi < $plafond_prime_renov_province_5_3 && $nbre_pers_foy == 5 ||
                    $rfi > $plafond_prime_renov_province_6_2 && $rfi < $plafond_prime_renov_province_6_3 && $nbre_pers_foy >= 6 ) {
                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Rouge);
                        $resultat->setCee($cee_cesi_Rouge);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Rouge + $cee_cesi_Rouge + $pouce_Non);

                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Rouge);
                        $resultat->setCee($cee_ssc_Rouge);
                        $resultat->setMontantTotal($renov_ssc_Rouge + $cee_ssc_Rouge + $pouce_Rouge);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Rouge);
                        $resultat->setCee($cee_ssc_Rouge);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_ssc_Rouge + $cee_ssc_Rouge + $pouce_Non);
                    }
                    $entityManager->persist($resultat);
                    $entityManager->flush();
                    return $resultat;
                } elseif ($rfi > $plafond_prime_renov_province_1_3 && $nbre_pers_foy == 1 ||
                    $rfi > $plafond_prime_renov_province_2_3 && $nbre_pers_foy == 2 ||
                    $rfi > $plafond_prime_renov_province_3_3 && $nbre_pers_foy == 3 ||
                    $rfi > $plafond_prime_renov_province_4_3 && $nbre_pers_foy == 4 ||
                    $rfi > $plafond_prime_renov_province_5_3 && $nbre_pers_foy == 5 ||
                    $rfi > $plafond_prime_renov_province_6_3 && $nbre_pers_foy >= 6) {
                    if ($produitVise == 'Eau chaude sanitaire') {
                        $resultat->setPrimeRenov($renov_cesi_Bleu);
                        $resultat->setCee($cee_cesi_Bleu);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_cesi_Bleu + $cee_cesi_Bleu + $pouce_Non);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie == 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Bleu);
                        $resultat->setCee($cee_ssc_Bleu);
                        $resultat->setMontantTotal($renov_ssc_Bleu + $cee_ssc_Bleu + $pouce_Rouge);
                    } elseif ($produitVise == 'Eau chaude sanitaire et chauffage' && $energie != 'Fioul') {
                        $resultat->setPrimeRenov($renov_ssc_Bleu);
                        $resultat->setCee($cee_ssc_Bleu);
                        $resultat->setCdpChauffage($pouce_Non);
                        $resultat->setMontantTotal($renov_ssc_Bleu + $cee_ssc_Bleu + $pouce_Non );
                    }

                    $entityManager->persist($resultat);
                    $entityManager->flush();
                    return $resultat;
                }
            }
        } else {
            #todo il n est pas elligible aux aides = ZERO
            $resultat->setUtilisateurSimulation($utilisateur);
            $resultat->setPrimeRenov($renov_non);
            $resultat->setCee($cee_non);
            $resultat->setCdpChauffage($pouce_Non);
            $resultat->setMontantTotal($renov_non + $cee_non + $pouce_Non);

            try {
                $entityManager->persist($resultat);
                $entityManager->flush();
            } catch (Exception $e) {

                return $this->redirectToRoute('create');
            }



        }
        return $resultat;
    }

}