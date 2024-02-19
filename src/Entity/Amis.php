<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmisRepository::class)]
class Amis
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'utilisateur')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Utilisateurs $idUtilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ami')]
    #[ORM\JoinColumn(name:"idUtilisateurAmi", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Utilisateurs $idUtilisateurAmi = null;

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

    public function getIdUtilisateurAmi(): ?Utilisateurs
    {
        return $this->idUtilisateurAmi;
    }

    public function setIdUtilisateurAmi(?Utilisateurs $idUtilisateurAmi): static
    {
        $this->idUtilisateurAmi = $idUtilisateurAmi;

        return $this;
    }
}
