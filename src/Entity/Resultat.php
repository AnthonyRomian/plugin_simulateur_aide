<?php

namespace App\Entity;

use App\Repository\ResultatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ResultatRepository::class)
 * @ORM\Table(name="wp_simulateur_aide_resultat")
 *
 */

//
class Resultat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"liste_utilisateurs"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"liste_utilisateurs"})
     */
    private $prime_renov;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"liste_utilisateurs"})
     */
    private $cee;


    /**
     * @ORM\Column(type="integer")
     * @Groups({"liste_utilisateurs"})
     */
    private $cdp_chauffage;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"liste_utilisateurs"})
     */
    private $montant_total;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateur::class, inversedBy="resultat", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $utilisateur_simulation;


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

    public function getUtilisateurSimulation(): ?Utilisateur
    {
        return $this->utilisateur_simulation;
    }

    public function setUtilisateurSimulation(Utilisateur $utilisateur_simulation): self
    {
        $this->utilisateur_simulation = $utilisateur_simulation;

        return $this;
    }
}
