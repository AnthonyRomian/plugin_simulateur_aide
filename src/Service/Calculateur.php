<?php


namespace App\Service;

use App\Entity\Resultat;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Calculateur extends AbstractController
{


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

        //-------------PRIME RENOV---------//
        $renov_Jaune = 14000;
        $renov_Gris = 11000;
        $renov_Rouge = 6000;
        $renov_Bleu = 0;

        //-------------CEE-----------------//
        $cee_Jaune = 1675;
        $cee_Gris = 1537;
        $cee_Rouge = 787;
        $cee_Bleu = 787;

        //------Coup de pouce fioul--------//
        $pouce_Jaune = 4000;
        $pouce_Gris = 4000;
        $pouce_Rouge = 2500;
        $pouce_Bleu = 2500;

        $dep = substr($cp, -5, 2);
        $resultat = new Resultat();


        /*var_dump($anciennete);
        var_dump($anciennete == true);

        var_dump($proprietaireOk);
        var_dump($proprietaireOk == true);*/


        if ($anciennete == true && $proprietaireOk == true ){
            #todo il est elligible aux aides
            var_dump($utilisateur);

            #todo si code postal ile de france
            if ( $dep == 75|| $dep == 77 || $dep == 78 || $dep == 91 || $dep == 92 || $dep == 93 || $dep == 94 || $dep == 95 ){
                # ile de france
                switch ($nbre_pers_foy) {
                    case 1:
                        if ($rfi > 0 && $rfi < 20593) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune+$cee_Jaune+$pouce_Jaune);
                            break;
                        } elseif ($rfi > 20593 && $rfi < 25068) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris+$cee_Gris+$pouce_Gris);
                            break;
                        } elseif ($rfi > 25068 && $rfi < 38184) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge+$cee_Rouge+$pouce_Rouge);
                            break;
                        } elseif ($rfi > 38184) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu+$cee_Bleu+$pouce_Bleu);
                            break;
                        }
                    case 2:
                        if ($rfi > 0 && $rfi < 30225) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune+$cee_Jaune+$pouce_Jaune);
                            break;
                        } elseif ($rfi > 30225 && $rfi < 36792) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris+$cee_Gris+$pouce_Gris);
                            break;
                        } elseif ($rfi > 36792 && $rfi < 56130) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge+$cee_Rouge+$pouce_Rouge);
                            break;
                        } elseif ($rfi > 56130) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu+$cee_Bleu+$pouce_Bleu);
                            break;
                        }
                    case 3:
                        if ($rfi > 0 && $rfi < 36297) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune+$cee_Jaune+$pouce_Jaune);
                            break;
                        } elseif ($rfi > 36297 && $rfi < 44188) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris+$cee_Gris+$pouce_Gris);
                            break;
                        } elseif ($rfi > 44188 && $rfi < 67585) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge+$cee_Rouge+$pouce_Rouge);
                            break;
                        } elseif ($rfi > 67585) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu+$cee_Bleu+$pouce_Bleu);
                            break;
                        }
                    case 4:
                        if ($rfi > 0 && $rfi < 42381) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune+$cee_Jaune+$pouce_Jaune);
                            break;
                        } elseif ($rfi > 42381 && $rfi < 51597) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris+$cee_Gris+$pouce_Gris);
                            break;
                        } elseif ($rfi > 51597 && $rfi < 79041) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge+$cee_Rouge+$pouce_Rouge);
                            break;
                        } elseif ($rfi > 79041) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu+$cee_Bleu+$pouce_Bleu);
                            break;
                        }
                    case 5:
                        if ($rfi > 0 && $rfi < 48488) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune+$cee_Jaune+$pouce_Jaune);
                            break;
                        } elseif ($rfi > 48488 && $rfi < 59026) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris+$cee_Gris+$pouce_Gris);
                            break;
                        } elseif ($rfi > 59026 && $rfi < 90496) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge+$cee_Rouge+$pouce_Rouge);
                            break;
                        } elseif ($rfi > 90496) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu+$cee_Bleu+$pouce_Bleu);
                            break;
                        }
                }
                $entityManager->persist($resultat);
                $entityManager->flush();

                var_dump($resultat);

                //return $this->redirectToRoute('resultat');

            } else {
                #hors ile de france
                switch ($nbre_pers_foy) {
                    case 1:
                        if ($rfi > 0 && $rfi < 14879) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune + $cee_Jaune + $pouce_Jaune);
                            break;
                        } elseif ($rfi > 14879 && $rfi < 19074) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris + $cee_Gris + $pouce_Gris);
                            break;
                        } elseif ($rfi > 19074 && $rfi < 29148) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge + $cee_Rouge + $pouce_Rouge);
                            break;
                        } elseif ($rfi > 29148) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu + $cee_Bleu + $pouce_Bleu);
                            break;
                        }
                    case 2:
                        if ($rfi > 0 && $rfi < 21760) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune + $cee_Jaune + $pouce_Jaune);
                            break;
                        } elseif ($rfi > 21760 && $rfi < 27896) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris + $cee_Gris + $pouce_Gris);
                            break;
                        } elseif ($rfi > 27896 && $rfi < 42848) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge + $cee_Rouge + $pouce_Rouge);
                            break;
                        } elseif ($rfi > 42848) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu + $cee_Bleu + $pouce_Bleu);
                            break;
                        }
                    case 3:
                        if ($rfi > 0 && $rfi < 26170) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune + $cee_Jaune + $pouce_Jaune);
                            break;
                        } elseif ($rfi > 26170 && $rfi < 33547) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris + $cee_Gris + $pouce_Gris);
                            break;
                        } elseif ($rfi > 33547 && $rfi < 51592) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge + $cee_Rouge + $pouce_Rouge);
                            break;
                        } elseif ($rfi > 51592) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu + $cee_Bleu + $pouce_Bleu);
                            break;
                        }
                    case 4:
                        if ($rfi > 0 && $rfi < 30572) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune + $cee_Jaune + $pouce_Jaune);
                            break;
                        } elseif ($rfi > 30572 && $rfi < 39192) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris + $cee_Gris + $pouce_Gris);
                            break;
                        } elseif ($rfi > 39192 && $rfi < 60336) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge + $cee_Rouge + $pouce_Rouge);
                            break;
                        } elseif ($rfi > 60336) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu + $cee_Bleu + $pouce_Bleu);
                            break;
                        }
                    case 5:
                        if ($rfi > 0 && $rfi < 34993) {
                            $resultat->setPrimeRenov($renov_Jaune);
                            $resultat->setCee($cee_Jaune);
                            $resultat->setCdpChauffage($pouce_Jaune);
                            $resultat->setMontantTotal($renov_Jaune + $cee_Jaune + $pouce_Jaune);
                            break;
                        } elseif ($rfi > 34993 && $rfi < 44860) {
                            $resultat->setPrimeRenov($renov_Gris);
                            $resultat->setCee($cee_Gris);
                            $resultat->setCdpChauffage($pouce_Gris);
                            $resultat->setMontantTotal($renov_Gris + $cee_Gris + $pouce_Gris);
                            break;
                        } elseif ($rfi > 44860 && $rfi < 69081) {
                            $resultat->setPrimeRenov($renov_Rouge);
                            $resultat->setCee($cee_Rouge);
                            $resultat->setCdpChauffage($pouce_Rouge);
                            $resultat->setMontantTotal($renov_Rouge + $cee_Rouge + $pouce_Rouge);
                            break;
                        } elseif ($rfi > 69081) {
                            $resultat->setPrimeRenov($renov_Bleu);
                            $resultat->setCee($cee_Bleu);
                            $resultat->setCdpChauffage($pouce_Bleu);
                            $resultat->setMontantTotal($renov_Bleu + $cee_Bleu + $pouce_Bleu);
                            break;
                        }
                }
                $entityManager->persist($resultat);
                $entityManager->flush();
                var_dump($resultat);
                //return $this->redirectToRoute('resultat');
            }
        }
        else {
            #todo il n est pas elligible aux aides
            #afficher page pour dire non aux aides

        }


    }

}