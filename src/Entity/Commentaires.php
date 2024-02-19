<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Utilisateurs $idUtilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name:"idRessource", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Ressources $idRessource = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name:"idCommentaireParent", referencedColumnName:"id", onDelete:"CASCADE", nullable: true)]
    private ?self $idCommentaireParent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'idCommentaireParent')]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateurs
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateurs $idUtilisateur): static
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

    public function getIdCommentaireParent(): ?self
    {
        return $this->idCommentaireParent;
    }

    public function setIdCommentaireParent(?self $idCommentaireParent): static
    {
        $this->idCommentaireParent = $idCommentaireParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(self $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdCommentaireParent($this);
        }

        return $this;
    }

    public function removeCommentaire(self $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdCommentaireParent() === $this) {
                $commentaire->setIdCommentaireParent(null);
            }
        }

        return $this;
    }
}
