<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_simulation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $type_bien;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ancienneteEligible;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $produit_vise;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nbre_salle_bain;

    /**
     * @ORM\Column(type="json")
     */
    private $chauffage = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $energie;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nbre_pers_foyer;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $revenu_fiscal;

    /**
     * @ORM\OneToOne(targetEntity=Resultat::class, mappedBy="utilisateur_simulation", cascade={"persist", "remove"})
     */
    private $resultat;

    /**
     * @var array (type="boolean" , "mapped=false)
     */
    private $agreeTerms = [];

    /**
     * @var array (type="boolean" , "mapped=false)
     */
    private $agreeEmail = [];

    /**
     * @return array
     */
    public function getAgreeTerms(): ?array
    {
        return $this->agreeTerms;
    }

    /**
     * @param array $agreeTerms
     */
    public function setAgreeTerms(array $agreeTerms): void
    {
        $this->agreeTerms = $agreeTerms;
    }

    /**
     * @return array
     */
    public function getAgreeEmail(): ?array
    {
        return $this->agreeEmail;
    }

    /**
     * @param array $agreeEmail
     */
    public function setAgreeEmail(array $agreeEmail): void
    {
        $this->agreeEmail = $agreeEmail;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateSimulation(): ?\DateTimeInterface
    {
        return $this->date_simulation;
    }

    public function setDateSimulation(\DateTimeInterface $date_simulation): self
    {
        $this->date_simulation = $date_simulation;

        return $this;
    }

    public function getProprietaire(): ?bool
    {
        return $this->proprietaire;
    }

    public function setProprietaire(bool $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getTypeBien(): ?string
    {
        return $this->type_bien;
    }

    public function setTypeBien(string $type_bien): self
    {
        $this->type_bien = $type_bien;

        return $this;
    }

    public function getAncienneteEligible(): ?bool
    {
        return $this->ancienneteEligible;
    }

    public function setAncienneteEligible(bool $ancienneteEligible): self
    {
        $this->ancienneteEligible = $ancienneteEligible;

        return $this;
    }


    public function getProduitVise(): ?string
    {
        return $this->produit_vise;
    }

    public function setProduitVise(string $produit_vise): self
    {
        $this->produit_vise = $produit_vise;

        return $this;
    }

    public function getNbreSalleBain(): ?string
    {
        return $this->nbre_salle_bain;
    }

    public function setNbreSalleBain(string $nbre_salle_bain): self
    {
        $this->nbre_salle_bain = $nbre_salle_bain;

        return $this;
    }

    public function getChauffage(): ?array
    {
        return $this->chauffage;
    }

    public function setChauffage(array $chauffage): self
    {
        $this->chauffage = $chauffage;

        return $this;
    }

    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    public function setEnergie(string $energie): self
    {
        $this->energie = $energie;

        return $this;
    }

    public function getNbrePersFoyer(): ?string
    {
        return $this->nbre_pers_foyer;
    }

    public function setNbrePersFoyer(string $nbre_pers_foyer): self
    {
        $this->nbre_pers_foyer = $nbre_pers_foyer;

        return $this;
    }

    public function getRevenuFiscal(): ?string
    {
        return $this->revenu_fiscal;
    }

    public function setRevenuFiscal(string $revenu_fiscal): self
    {
        $this->revenu_fiscal = $revenu_fiscal;

        return $this;
    }

    public function getResultat(): ?Resultat
    {
        return $this->resultat;
    }

    public function setResultat(Resultat $resultat): self
    {
        // set the owning side of the relation if necessary
        if ($resultat->getUtilisateurSimulation() !== $this) {
            $resultat->setUtilisateurSimulation($this);
        }

        $this->resultat = $resultat;

        return $this;
    }
}
