<?php

namespace App\Entity;

use App\Repository\ResultatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultatRepository::class)
 */
class Resultat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $prime_renov;

    /**
     * @ORM\Column(type="integer")
     */
    private $cee;

    /**
     * @ORM\Column(type="integer")
     */
    private $cdp_chauffage;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant_total;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrimeRenov(): ?int
    {
        return $this->prime_renov;
    }

    public function setPrimeRenov(int $prime_renov): self
    {
        $this->prime_renov = $prime_renov;

        return $this;
    }

    public function getCee(): ?int
    {
        return $this->cee;
    }

    public function setCee(int $cee): self
    {
        $this->cee = $cee;

        return $this;
    }

    public function getCdpChauffage(): ?int
    {
        return $this->cdp_chauffage;
    }

    public function setCdpChauffage(int $cdp_chauffage): self
    {
        $this->cdp_chauffage = $cdp_chauffage;

        return $this;
    }

    public function getMontantTotal(): ?int
    {
        return $this->montant_total;
    }

    public function setMontantTotal(int $montant_total): self
    {
        $this->montant_total = $montant_total;

        return $this;
    }
}
