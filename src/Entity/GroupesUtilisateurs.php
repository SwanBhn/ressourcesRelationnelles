<?php

namespace App\Entity;

use App\Repository\GroupesUtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupesUtilisateursRepository::class)]
class GroupesUtilisateurs
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'groupesUtilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $idUtilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'groupesUtilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupes $idGroupe = null;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    public function getIdUtilisateur(): ?Utilisateurs
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateurs $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdGroupe(): ?Groupes
    {
        return $this->idGroupe;
    }

    public function setIdGroupe(?Groupes $idGroupe): static
    {
        $this->idGroupe = $idGroupe;

        return $this;
    }
}
