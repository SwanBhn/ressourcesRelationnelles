<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: "dateEnvoi")]
    private ?\DateTimeInterface $dateEnvoi = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(name:"idUtilisateurEnvoie", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateurEnvoie = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(name:"idUtilisateurRecois", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateurRecois = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeInterface $dateEnvoi): static
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    public function getIdUtilisateurEnvoie(): ?User
    {
        return $this->idUtilisateurEnvoie;
    }

    public function setIdUtilisateurEnvoie(?User $idUtilisateurEnvoie): static
    {
        $this->idUtilisateurEnvoie = $idUtilisateurEnvoie;

        return $this;
    }

    public function getIdUtilisateurRecois(): ?User
    {
        return $this->idUtilisateurRecois;
    }

    public function setIdUtilisateurRecois(?User $idUtilisateurRecois): static
    {
        $this->idUtilisateurRecois = $idUtilisateurRecois;

        return $this;
    }
}
