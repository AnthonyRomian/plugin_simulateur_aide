<?php


namespace App\Service;


use Twig\Environment;


class Graphique
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function GraphRender($utilisateurs_data): array
    {

        dd($utilisateurs_data);
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

        // ------------RECUP LES NOMBRES DE DEPARTEMENTS---------
        $count_dep = array_count_values($tableauCP);

        for ($i=0; $i<sizeof($count_dep); $i++) {
            $compteur_departement[] = $count_dep[$tableau_sans_doublon[$i]];
        }

        return $utilisateurs_data = [
            'nbre_sanit'=> $nbre_sanit,
            'nbre_chauf'=> $nbre_sanit_chauff,
            'tableau_sans_doublon'=> json_encode($tableau_sans_doublon),
            'tableau_sans_doublon2'=> $tableau_sans_doublon,
            'count_dep'=> json_encode($compteur_departement)
        ];


    }
}