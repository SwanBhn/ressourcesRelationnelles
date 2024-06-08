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
    #[ORM\ManyToOne(inversedBy: 'user')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ami')]
    #[ORM\JoinColumn(name:"idUtilisateurAmi", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateurAmi = null;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdUtilisateurAmi(): ?User
    {
        return $this->idUtilisateurAmi;
    }

    public function setIdUtilisateurAmi(?User $idUtilisateurAmi): static
    {
        $this->idUtilisateurAmi = $idUtilisateurAmi;

        return $this;
    }
}
