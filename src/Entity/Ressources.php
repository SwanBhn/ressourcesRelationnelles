<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourcesRepository::class)]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: "dateCreationRessource")]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(options: ["default" => false], name: "estPubliee")]
    private ?bool $estPubliee = null;

    #[ORM\Column(options: ["default" => false],name: "estValidee")]
    private ?bool $estValidee = null;

    #[ORM\Column(options: ["default" => false], name: "estRestreinte")]
    private ?bool $estRestreinte = null;

    #[ORM\Column(options: ["default" => false], name: "estExploitee")]
    private ?bool $estExploitee = null;

    #[ORM\Column(options: ["default" => false], name:"estArchivee")]
    private ?bool $estArchivee = null;

    #[ORM\Column(options: ["default" => false], name:"estDesactivee")]
    private ?bool $estDesactivee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $multimedia = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(name:"idUtilisateur", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?User $idUtilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(name:"idCategorie", referencedColumnName:"id", onDelete:"CASCADE", nullable: false)]
    private ?Categories $idCategorie = null;

    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'idRessource')]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Partage::class, mappedBy: 'idRessource')]
    private Collection $partages;

    #[ORM\OneToMany(targetEntity: Enregistrer::class, mappedBy: 'idRessource')]
    private Collection $enregistrers;

    #[ORM\OneToMany(targetEntity: Participer::class, mappedBy: 'idRessource')]
    private Collection $participers;

    #[ORM\OneToMany(targetEntity: GroupesRessources::class, mappedBy: 'idRessource')]
    private Collection $groupesRessources;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->partages = new ArrayCollection();
        $this->enregistrers = new ArrayCollection();
        $this->participers = new ArrayCollection();
        $this->groupesRessources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
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

    public function isEstPubliee(): ?bool
    {
        return $this->estPubliee;
    }

    public function setEstPubliee(bool $estPubliee): static
    {
        $this->estPubliee = $estPubliee;

        return $this;
    }

    public function isEstValidee(): ?bool
    {
        return $this->estValidee;
    }

    public function setEstValidee(bool $estValidee): static
    {
        $this->estValidee = $estValidee;

        return $this;
    }

    public function isEstRestreinte(): ?bool
    {
        return $this->estRestreinte;
    }

    public function setEstRestreinte(bool $estRestreinte): static
    {
        $this->estRestreinte = $estRestreinte;

        return $this;
    }

    public function isEstExploitee(): ?bool
    {
        return $this->estExploitee;
    }

    public function setEstExploitee(bool $estExploitee): static
    {
        $this->estExploitee = $estExploitee;

        return $this;
    }

    public function isEstArchivee(): ?bool
    {
        return $this->estArchivee;
    }

    public function setEstArchivee(bool $estArchivee): static
    {
        $this->estArchivee = $estArchivee;

        return $this;
    }

    public function isEstDesactivee(): ?bool
    {
        return $this->estDesactivee;
    }

    public function setEstDesactivee(bool $estDesactivee): static
    {
        $this->estDesactivee = $estDesactivee;

        return $this;
    }

    public function getMultimedia(): ?string
    {
        return $this->multimedia;
    }

    public function setMultimedia(?string $multimedia): static
    {
        $this->multimedia = $multimedia;

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

    public function getIdCategorie(): ?Categories
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categories $idCategorie): static
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdRessource($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdRessource() === $this) {
                $commentaire->setIdRessource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partage>
     */
    public function getPartages(): Collection
    {
        return $this->partages;
    }

    public function addPartage(Partage $partage): static
    {
        if (!$this->partages->contains($partage)) {
            $this->partages->add($partage);
            $partage->setIdRessource($this);
        }

        return $this;
    }

    public function removePartage(Partage $partage): static
    {
        if ($this->partages->removeElement($partage)) {
            // set the owning side to null (unless already changed)
            if ($partage->getIdRessource() === $this) {
                $partage->setIdRessource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Enregistrer>
     */
    public function getEnregistrers(): Collection
    {
        return $this->enregistrers;
    }

    public function addEnregistrer(Enregistrer $enregistrer): static
    {
        if (!$this->enregistrers->contains($enregistrer)) {
            $this->enregistrers->add($enregistrer);
            $enregistrer->setIdRessource($this);
        }

        return $this;
    }

    public function removeEnregistrer(Enregistrer $enregistrer): static
    {
        if ($this->enregistrers->removeElement($enregistrer)) {
            // set the owning side to null (unless already changed)
            if ($enregistrer->getIdRessource() === $this) {
                $enregistrer->setIdRessource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participer>
     */
    public function getParticipers(): Collection
    {
        return $this->participers;
    }

    public function addParticiper(Participer $participer): static
    {
        if (!$this->participers->contains($participer)) {
            $this->participers->add($participer);
            $participer->setIdRessource($this);
        }

        return $this;
    }

    public function removeParticiper(Participer $participer): static
    {
        if ($this->participers->removeElement($participer)) {
            // set the owning side to null (unless already changed)
            if ($participer->getIdRessource() === $this) {
                $participer->setIdRessource(null);
            }
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
            $groupesRessource->setIdRessource($this);
        }

        return $this;
    }

    public function removeGroupesRessource(GroupesRessources $groupesRessource): static
    {
        if ($this->groupesRessources->removeElement($groupesRessource)) {
            // set the owning side to null (unless already changed)
            if ($groupesRessource->getIdRessource() === $this) {
                $groupesRessource->setIdRessource(null);
            }
        }

        return $this;
    }
}
