<?php


namespace App\Data;

use Doctrine\ORM\Mapping as ORM;

class SearchData
{
    /**
     * @var string
     */
    public $q  = '';

    /**
     * @ORM\Column(type="string")
     */
    public $departement ;

    /**
     * @var boolean
     */
//    public $eauChaudeSanitaire = false;

    /**
     * @var boolean
     */
//    public $eauChaudeSanitaireChauffage = false;

    /**
     * @var boolean
     */
//    public $eauChaudeSanitaireElectricite = false;

    /**
     * @var
     */
    public $produitVise ;

    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }

    /**
     * @return mixed
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * @var
     */
    public $energie ;

    /*/**
     * @return bool
     */
    /*public function isEauChaudeSanitaire(): bool
    {
        return $this->eauChaudeSanitaire;
    }*/

    /**
     * @return bool
     */
    /*public function isEauChaudeSanitaireChauffage(): bool
    {
        return $this->eauChaudeSanitaireChauffage;
    }*/

    /**
     * @return bool
     */
    /*public function isEauChaudeSanitaireElectricite(): bool
    {
        return $this->eauChaudeSanitaireElectricite;
    }*/

    /**
     * @return mixed
     */
    public function getEnergie()
    {
        return $this->energie;
    }

    /**
     * @return mixed
     */
    public function getProduitVise()
    {
        return $this->produitVise;
    }



}