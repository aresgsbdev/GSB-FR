<?php

namespace App\Entity;

use App\Repository\EtatFicheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatFicheRepository::class)
 */
class EtatFiche
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=FicheFrais::class, mappedBy="etatFicheFrais", orphanRemoval=true)
     */
    private $ficheFrais;

    public function __construct()
    {
        $this->ficheFrais = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|FicheFrais[]
     */
    public function getFicheFrais(): Collection
    {
        return $this->ficheFrais;
    }

    public function addFicheFrais(FicheFrais $ficheFrais): self
    {
        if (!$this->ficheFrais->contains($ficheFrais)) {
            $this->ficheFrais[] = $ficheFrais;
            $ficheFrais->setEtatFicheFrais($this);
        }

        return $this;
    }

    public function removeFicheFrais(FicheFrais $ficheFrais): self
    {
        if ($this->ficheFrais->removeElement($ficheFrais)) {
            // set the owning side to null (unless already changed)
            if ($ficheFrais->getEtatFicheFrais() === $this) {
                $ficheFrais->setEtatFicheFrais(null);
            }
        }

        return $this;
    }
}
