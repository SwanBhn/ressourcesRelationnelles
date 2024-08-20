<?php

namespace App\Entity;

use App\Repository\GroupesUtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupesUtilisateursRepository::class)]
class GroupesUtilisateurs
{
 

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'groupesUtilisateurs')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'groupesUtilisateurs')]
    #[ORM\JoinColumn(name:"idGroupe", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Groupes $idGroupe = null;



    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): static
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
