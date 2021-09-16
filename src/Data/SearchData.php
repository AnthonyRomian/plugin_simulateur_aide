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
    public $eauChaudeSanitaire = false;

    /**
     * @var boolean
     */
    public $eauChaudeSanitaireChauffage = false;


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

    /**
     * @return bool
     */
    public function isEauChaudeSanitaire(): bool
    {
        return $this->eauChaudeSanitaire;
    }

    /**
     * @return bool
     */
    public function isEauChaudeSanitaireChauffage(): bool
    {
        return $this->eauChaudeSanitaireChauffage;
    }

    /**
     * @return mixed
     */
    public function getEnergie()
    {
        return $this->energie;
    }



}