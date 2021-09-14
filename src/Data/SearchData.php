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
     * @var array
     */
    public $energie = [];

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

    public function getEnergie(): ?array
    {
        return $this->energie;
    }



}