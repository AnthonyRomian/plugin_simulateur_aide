<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @ORM\Table(name="wp_simulateur_aide_utilisateur")
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"liste_utilisateurs"})
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner votre nom")
     * @Assert\NotNull (message="Veuillez renseigner votre nom")
     * @Assert\Length(
     *     min=1,
     *     max=50,
     *     minMessage="Renseignez un prénom valide",
     *     maxMessage="Renseignez un prénom valide"
     * )
     * @Assert\Regex(pattern="/^([^0-9]*)$/", message="Lettres seulement")
     * @Groups({"liste_utilisateurs"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner votre prénom")
     * @Assert\Length(
     *     min=1,
     *     max=50,
     *     minMessage="Renseignez un prénom valide",
     *     maxMessage="Renseignez un prénom valide"
     * )
     * @Assert\Regex(pattern="/^([^0-9]*)$/", message="Lettres seulement")
     * @Groups({"liste_utilisateurs"})
     */
    private $prenom;

    /**
     *
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank(message="Renseignez votre code postal")
     * @Assert\Length(
     *     min=5,
     *     max=5,
     *     minMessage="Renseigner un code postal valide",
     *     maxMessage="Renseigner un code postal valide"
     * )
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Nombre seulement")
     * @Groups({"liste_utilisateurs"})
     */
    private $code_postal;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Renseignez votre ville")
     * @Assert\Length(
     *     min=1,
     *     max=50,
     *     minMessage="Rentrez un nom de ville",
     *     maxMessage="Rentrez un nom de ville"
     * )
     * @Groups({"liste_utilisateurs"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=13)
     * @Assert\NotBlank(message="Renseignez votre numero de telephone")
     * @Assert\Length(
     *     min=10,
     *     max=13,
     *     minMessage="Rentrez un numero de téléphone valide",
     *     maxMessage="Rentrez un numero de téléphone valide"
     * )
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Nombre seulement")
     * @Groups({"liste_utilisateurs"})
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Saisissez un email")
     * @Assert\Email(message="Saisissez un email valide")
     * @Assert\Length(
     *     min=1,
     *     max=50,
     *     minMessage="Rentrez un numero de téléphone valide",
     *     maxMessage="Rentrez un numero de téléphone valide"
     * )
     * @Groups({"liste_utilisateurs"})
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"liste_utilisateurs"})
     */
    private $date_simulation;

    /**
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull (message="Renseignez si vous etes propriétaire ou locataire")
     * @Groups({"liste_utilisateurs"})
     */
    private $proprietaire;

    /**
     *
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Renseignez votre bien")
     * @Groups({"liste_utilisateurs"})
     */
    private $type_bien;

    /**
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Renseignez l'anciennetée de votre bien")
     * @Groups({"liste_utilisateurs"})
     */
    private $ancienneteEligible;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull (message="Renseignez le produit voulu")
     * @Groups({"liste_utilisateurs"})
     */
    private $produit_vise;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Assert\NotBlank(message="Renseignez un nombre")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message="doit etre supérieur à 0")
     * @Groups({"liste_utilisateurs"})
     */
    private $nbre_salle_bain;

    /**
     * @ORM\Column(type="json")
     * @Groups({"liste_utilisateurs"})
     */
    private $chauffage = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"liste_utilisateurs"})
     */
    private $energie;

    /**
     *
     * @ORM\Column(type="integer", length=255)
     * @Assert\NotBlank (message="Renseignez un nombre")
     * @Assert\GreaterThan(value = 0, message="La valeur doit etre supérieur à 0")
     * @Groups({"liste_utilisateurs"})
     */
    private $nbre_pers_foyer;

    /**
     *
     * @ORM\Column(type="integer", length=255)
     * @Assert\NotBlank(message="Renseignez votre revenu fiscal")
     * @Assert\GreaterThan(value = 0, message="La valeur doit etre supérieur à 0")
     * @Groups({"liste_utilisateurs"})
     */
    private $revenu_fiscal;

    /**
     * @ORM\OneToOne(targetEntity=Resultat::class, mappedBy="utilisateur_simulation", cascade={"persist", "remove"})
     * @Groups({"liste_utilisateurs"})
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rappel;

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

    public function getDateSimulation(): ?DateTimeInterface
    {
        return $this->date_simulation;
    }

    public function setDateSimulation(DateTimeInterface $date_simulation): self
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

    public function getRappel(): ?bool
    {
        return $this->rappel;
    }

    public function setRappel(?bool $rappel): self
    {
        $this->rappel = $rappel;

        return $this;
    }
}
