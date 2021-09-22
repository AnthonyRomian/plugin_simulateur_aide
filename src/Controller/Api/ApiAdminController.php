<?php

namespace App\Controller\Api;


use App\Entity\Utilisateur;
use App\Repository\ResultatRepository;
use App\Repository\UtilisateurRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiAdminController extends AbstractController
{

    // Afficher les villes
    /**
     * @Route("/api/utilisateurs", name="api_utilisateurs_list", methods={"GET"})
     */
    public function liste(ResultatRepository $resultatRepository,
                          UtilisateurRepository $utilisateurRepository,
                         Request $request,
                        SerializerInterface $serializer
    )
    {
        $utilisateurs = $utilisateurRepository->findAll();
        $resultats = $resultatRepository->findAll();
         $json = $serializer->serialize($utilisateurs, 'json', ['groups' => 'liste_utilisateurs']);

        return  new JsonResponse($json, 200, [], true);

    }

    // Stats des statistiques
    /**
     * @Route("/api/stats", name="api_utilisateurs_stats", methods={"GET"})
     */
    public function statistiques(UtilisateurRepository $utilisateurRepository,
                                 SerializerInterface $serializer,
                                 Request $request
    )
    {

        $utilisateurs_data = $utilisateurRepository->findAll();

        //nombre total
        $nbre_total = sizeof($utilisateurs_data);
        $nbre_sanit = 0;
        $nbre_sanit_chauff = 0;

        //nombre de sanitaire simple
        //-------------- GRAPH PRODUIT VISE ------------------
        for ($i=0; $i<$nbre_total; $i++){
            $produit_vise = $utilisateurs_data[$i]->getProduitVise();
            if ( $produit_vise == "Eau chaude sanitaire et chauffage") {
                $nbre_sanit_chauff++;
            } else {
                $nbre_sanit++;
            }
        }
        //-------------- GRAPH DEPARTEMENT ------------------

        $tableauCP = [];
        for ($i=0; $i<$nbre_total; $i++) {
            $cp = $utilisateurs_data[$i]->getCodePostal();
            $dep = substr($cp, -5, 2);
            $departement = intval($dep);
            $tableauCP[$i] = $departement;
        }


        $tableau_sans_doublon = [];

        // ------------RECUP LES NOM DES DEPARTEMENTS---------
        // parcourir chaque valeur
        for ($i=0; $i<sizeof($tableauCP); $i++) {
            if ($i == 0) {
                // mettre la premiere dans un tableau
                $tableau_sans_doublon[] = $tableauCP[$i];
            } else {
                // comparé la seconde avec la précédente
                if ($tableauCP[$i] != $tableauCP[$i-1]) {
                    // si elle est differente mettre dans le tableau
                    $tableau_sans_doublon[] = $tableauCP[$i];
                }
            }
        }
        $tableau_sans_doublon = array_unique($tableau_sans_doublon);

        $tableau_sans_doublon = array_values($tableau_sans_doublon);

        // ------------RECUP LES NOMBRES DE DEPARTEMENTS---------
        $count_dep = array_count_values($tableauCP);

        for ($i=0; $i<sizeof($count_dep); $i++) {
            $compteur_departement[] = $count_dep[$tableau_sans_doublon[$i]];
        }
        $data_graph = ['nbre_sanit'=> $nbre_sanit,'nbre_chauf'=> $nbre_sanit_chauff, 'tableau_sans_doublon'=> $tableau_sans_doublon ,'tableau_sans_doublon_2'=> json_encode($tableau_sans_doublon), 'count_dep'=> json_encode($compteur_departement) ];

        $json = $serializer->serialize($data_graph, 'json', ['groups' => 'liste_utilisateurs']);

        return  new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/api/utilisateur/profil/{id}", name="utilisateur_profil", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur,
                        SerializerInterface $serializer
    ): Response
    {

        $json = $serializer->serialize($utilisateur, 'json', ['groups' => 'liste_utilisateurs']);

        return  new JsonResponse($json, 200, [], true);
    }

}