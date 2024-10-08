<?php

namespace App\Entity;

use App\Repository\GroupesRessourcesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupesRessourcesRepository::class)]
class GroupesRessources
{


    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'groupesRessources')]
    #[ORM\JoinColumn(name:"idRessource", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Ressources $idRessource = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'groupesRessources')]
    #[ORM\JoinColumn(name:"idGroupe", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Groupes $idGroupe = null;


    public function getIdRessource(): ?Ressources
    {
        return $this->idRessource;
    }

    public function setIdRessource(?Ressources $idRessource): static
    {
        $this->idRessource = $idRessource;

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
