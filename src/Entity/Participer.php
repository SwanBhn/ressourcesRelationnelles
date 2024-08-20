<?php

namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
class Participer
{
    

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participers')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participers')]
    #[ORM\JoinColumn(name:"idRessource", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Ressources $idRessource = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: "dateParticipation")]
    private ?\DateTimeInterface $dateParticipation = null;

   

    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdRessource(): ?Ressources
    {
        return $this->idRessource;
    }

    public function setIdRessource(?Ressources $idRessource): static
    {
        $this->idRessource = $idRessource;

        return $this;
    }

    public function getDateParticipation(): ?\DateTimeInterface
    {
        return $this->dateParticipation;
    }

    public function setDateParticipation(\DateTimeInterface $dateParticipation): static
    {
        $this->dateParticipation = $dateParticipation;

        return $this;
    }
}
