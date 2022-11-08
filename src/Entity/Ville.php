<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 5)]
    private ?string $cp = null;

    #[ORM\Column(length: 150)]
    private ?string $lib_departement = null;

    #[ORM\Column(length: 10)]
    private ?string $num_departement = null;

    #[ORM\Column(length: 150)]
    private ?string $lib_region = null;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Etablissement::class)]
    private Collection $etablissements;

    public function __construct()
    {
        $this->etablissements = new ArrayCollection();
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

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getLibDepartement(): ?string
    {
        return $this->lib_departement;
    }

    public function setLibDepartement(string $lib_departement): self
    {
        $this->lib_departement = $lib_departement;

        return $this;
    }

    public function getNumDepartement(): ?string
    {
        return $this->num_departement;
    }

    public function setNumDepartement(string $num_departement): self
    {
        $this->num_departement = $num_departement;

        return $this;
    }

    public function getLibRegion(): ?string
    {
        return $this->lib_region;
    }

    public function setLibRegion(string $lib_region): self
    {
        $this->lib_region = $lib_region;

        return $this;
    }

    /**
     * @return Collection<int, Etablissement>
     */
    public function getEtablissements(): Collection
    {
        return $this->etablissements;
    }

    public function addEtablissement(Etablissement $etablissement): self
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements->add($etablissement);
            $etablissement->setVille($this);
        }

        return $this;
    }

    public function removeEtablissement(Etablissement $etablissement): self
    {
        if ($this->etablissements->removeElement($etablissement)) {
            // set the owning side to null (unless already changed)
            if ($etablissement->getVille() === $this) {
                $etablissement->setVille(null);
            }
        }

        return $this;
    }
}
