<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(min: 5)]
    #[Assert\NotNull]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Unique]
    #[Assert\NotNull]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotNull]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $adresse = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotNull]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $actif = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $accueil = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'etablissements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Ville $ville = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'etablissements')]
    #[Assert\NotNull]
    private Collection $categorie;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'etablissements')]
    private Collection $users;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function isAccueil(): ?bool
    {
        return $this->accueil;
    }

    public function setAccueil(bool $accueil): self
    {
        $this->accueil = $accueil;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getVille(): ?ville
    {
        return $this->ville;
    }

    public function setVille(?ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addEtablissement($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeEtablissement($this);
        }

        return $this;
    }
}
