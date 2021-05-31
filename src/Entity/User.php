<?php

namespace App\Entity;

use JMS\Serializer\Annotation\MaxDepth;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEmbauche;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=FicheFrais::class, mappedBy="utilisateurFicheFrais", orphanRemoval=true)
     * @MaxDepth(0)
     */
    private $ficheFrais;

    /**
     * @ORM\OneToMany(targetEntity=Messagerie::class, mappedBy="utilisateurDestinataireMessagerie", orphanRemoval=true)
     * @MaxDepth(0)
     */
    private $messageries;

    /**
     * @ORM\OneToMany(targetEntity=Justificatif::class, mappedBy="utilisateurJustificatif", orphanRemoval=true)
     * @MaxDepth(0)
     */
    private $justificatifs;

    /**
     * @ORM\OneToMany(targetEntity=LigneFraisHorsForfait::class, mappedBy="utilisateurLigneFraisHorsForfait", orphanRemoval=true)
     * @MaxDepth(0)
     */
    private $ligneFraisHorsForfaits;

    /**
     * @ORM\OneToMany(targetEntity=LigneFraisForfait::class, mappedBy="utilisateurLigneFraisForfait", orphanRemoval=true)
     * @MaxDepth(0)
     */
    private $ligneFraisForfaits;

    public function __construct()
    {
        $this->ficheFrais = new ArrayCollection();
        $this->messageries = new ArrayCollection();
        $this->justificatifs = new ArrayCollection();
        $this->ligneFraisHorsForfaits = new ArrayCollection();
        $this->ligneFraisForfaits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): self
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|FicheFrais[]
     */
    public function getFicheFrais(): Collection
    {
        return $this->ficheFrais;
    }

    public function addFicheFrai(FicheFrais $ficheFrai): self
    {
        if (!$this->ficheFrais->contains($ficheFrai)) {
            $this->ficheFrais[] = $ficheFrai;
            $ficheFrai->setUtilisateurFicheFrais($this);
        }

        return $this;
    }

    public function removeFicheFrai(FicheFrais $ficheFrai): self
    {
        if ($this->ficheFrais->removeElement($ficheFrai)) {
            // set the owning side to null (unless already changed)
            if ($ficheFrai->getUtilisateurFicheFrais() === $this) {
                $ficheFrai->setUtilisateurFicheFrais(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messagerie[]
     */
    public function getMessageries(): Collection
    {
        return $this->messageries;
    }

    public function addMessagery(Messagerie $messagery): self
    {
        if (!$this->messageries->contains($messagery)) {
            $this->messageries[] = $messagery;
            $messagery->setUtilisateurDestinataireMessagerie($this);
        }

        return $this;
    }

    public function removeMessagery(Messagerie $messagery): self
    {
        if ($this->messageries->removeElement($messagery)) {
            // set the owning side to null (unless already changed)
            if ($messagery->getUtilisateurDestinataireMessagerie() === $this) {
                $messagery->setUtilisateurDestinataireMessagerie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Justificatif[]
     */
    public function getJustificatifs(): Collection
    {
        return $this->justificatifs;
    }

    public function addJustificatif(Justificatif $justificatif): self
    {
        if (!$this->justificatifs->contains($justificatif)) {
            $this->justificatifs[] = $justificatif;
            $justificatif->setUtilisateurJustificatif($this);
        }

        return $this;
    }

    public function removeJustificatif(Justificatif $justificatif): self
    {
        if ($this->justificatifs->removeElement($justificatif)) {
            // set the owning side to null (unless already changed)
            if ($justificatif->getUtilisateurJustificatif() === $this) {
                $justificatif->setUtilisateurJustificatif(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneFraisHorsForfait[]
     */
    public function getLigneFraisHorsForfaits(): Collection
    {
        return $this->ligneFraisHorsForfaits;
    }

    public function addLigneFraisHorsForfait(LigneFraisHorsForfait $ligneFraisHorsForfait): self
    {
        if (!$this->ligneFraisHorsForfaits->contains($ligneFraisHorsForfait)) {
            $this->ligneFraisHorsForfaits[] = $ligneFraisHorsForfait;
            $ligneFraisHorsForfait->setUtilisateurLigneFraisHorsForfait($this);
        }

        return $this;
    }

    public function removeLigneFraisHorsForfait(LigneFraisHorsForfait $ligneFraisHorsForfait): self
    {
        if ($this->ligneFraisHorsForfaits->removeElement($ligneFraisHorsForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneFraisHorsForfait->getUtilisateurLigneFraisHorsForfait() === $this) {
                $ligneFraisHorsForfait->setUtilisateurLigneFraisHorsForfait(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneFraisForfait[]
     */
    public function getLigneFraisForfaits(): Collection
    {
        return $this->ligneFraisForfaits;
    }

    public function addLigneFraisForfait(LigneFraisForfait $ligneFraisForfait): self
    {
        if (!$this->ligneFraisForfaits->contains($ligneFraisForfait)) {
            $this->ligneFraisForfaits[] = $ligneFraisForfait;
            $ligneFraisForfait->setUtilisateurLigneFraisForfait($this);
        }

        return $this;
    }

    public function removeLigneFraisForfait(LigneFraisForfait $ligneFraisForfait): self
    {
        if ($this->ligneFraisForfaits->removeElement($ligneFraisForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneFraisForfait->getUtilisateurLigneFraisForfait() === $this) {
                $ligneFraisForfait->setUtilisateurLigneFraisForfait(null);
            }
        }

        return $this;
    }
}
