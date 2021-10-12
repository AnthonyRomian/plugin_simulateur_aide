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