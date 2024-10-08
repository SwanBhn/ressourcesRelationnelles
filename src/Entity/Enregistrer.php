<?php

namespace App\Entity;

use App\Repository\EnregistrerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnregistrerRepository::class)]
class Enregistrer
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'enregistrers')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'enregistrers')]
    #[ORM\JoinColumn(name:"idRessource", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Ressources $idRessource = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: "dateFavoris")]
    private ?\DateTimeInterface $dateFavoris = null;


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

    public function getDateFavoris(): ?\DateTimeInterface
    {
        return $this->dateFavoris;
    }

    public function setDateFavoris(\DateTimeInterface $dateFavoris): static
    {
        $this->dateFavoris = $dateFavoris;

        return $this;
    }
}
