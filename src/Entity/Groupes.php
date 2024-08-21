<?php

namespace App\Entity;

use App\Repository\GroupesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupesRepository::class)]
class Groupes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateur = null;

    #[ORM\OneToMany(targetEntity: GroupesUtilisateurs::class, mappedBy: 'idGroupe')]
    private Collection $groupesUtilisateurs;

    #[ORM\OneToMany(targetEntity: GroupesRessources::class, mappedBy: 'idGroupe')]
    private Collection $groupesRessources;

    public function __construct()
    {
        $this->groupesUtilisateurs = new ArrayCollection();
        $this->groupesRessources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    /**
     * @return Collection<int, GroupesUtilisateurs>
     */
    public function getGroupesUtilisateurs(): Collection
    {
        return $this->groupesUtilisateurs;
    }

    public function addGroupesUtilisateur(GroupesUtilisateurs $groupesUtilisateur): static
    {
        if (!$this->groupesUtilisateurs->contains($groupesUtilisateur)) {
            $this->groupesUtilisateurs->add($groupesUtilisateur);
            $groupesUtilisateur->setIdGroupe($this);
        }

        return $this;
    }

    public function removeGroupesUtilisateur(GroupesUtilisateurs $groupesUtilisateur): static
    {
        if ($this->groupesUtilisateurs->removeElement($groupesUtilisateur) && $groupesUtilisateur->getIdGroupe() === $this) {
            $groupesUtilisateur->setIdGroupe(null);
        }

        return $this;
    }


    /**
     * @return Collection<int, GroupesRessources>
     */
    public function getGroupesRessources(): Collection
    {
        return $this->groupesRessources;
    }

    public function addGroupesRessource(GroupesRessources $groupesRessource): static
    {
        if (!$this->groupesRessources->contains($groupesRessource)) {
            $this->groupesRessources->add($groupesRessource);
            $groupesRessource->setIdGroupe($this);
        }

        return $this;
    }

    public function removeGroupesRessource(GroupesRessources $groupesRessource): static
    {
        if ($this->groupesRessources->removeElement($groupesRessource) && $groupesRessource->getIdGroupe() === $this) {
            $groupesRessource->setIdGroupe(null);
        }

        return $this;
    }
}
