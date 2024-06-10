<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cette adresse email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(options: ["default" => false], name: "estDesactive")]
    private ?bool $estDesactive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;
    
    #[ORM\OneToMany(targetEntity: Ressources::class, mappedBy: 'idUtilisateur')]
    private Collection $ressources;

    #[ORM\OneToMany(targetEntity: Groupes::class, mappedBy: 'idUtilisateur')]
    private Collection $groupes;

    #[ORM\OneToMany(targetEntity: Messages::class, mappedBy: 'idUtilisateurEnvoie')]
    private Collection $messages;

    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'idUtilisateur')]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Amis::class, mappedBy: 'idUtilisateur')]
    private Collection $utilisateur;

    #[ORM\OneToMany(targetEntity: Amis::class, mappedBy: 'idUtilisateurAmi')]
    private Collection $ami;

    #[ORM\OneToMany(targetEntity: Partage::class, mappedBy: 'idUtilisateur')]
    private Collection $partages;

    #[ORM\OneToMany(targetEntity: Enregistrer::class, mappedBy: 'idUtilisateur')]
    private Collection $enregistrers;

    #[ORM\OneToMany(targetEntity: Participer::class, mappedBy: 'idUtilisateur')]
    private Collection $participers;

    #[ORM\OneToMany(targetEntity: GroupesUtilisateurs::class, mappedBy: 'idUtilisateur')]
    private Collection $groupesUtilisateurs;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->utilisateur = new ArrayCollection();
        $this->ami = new ArrayCollection();
        $this->partages = new ArrayCollection();
        $this->enregistrers = new ArrayCollection();
        $this->participers = new ArrayCollection();
        $this->groupesUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function isEstDesactive(): ?bool
    {
        return $this->estDesactive;
    }

    public function setEstDesactive(bool $estDesactive): static
    {
        $this->estDesactive = $estDesactive;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    
    /**
     * @return Collection<int, Ressources>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressources $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeRessource(Ressources $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getIdUtilisateur() === $this) {
                $ressource->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Groupes>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupes $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getIdUtilisateur() === $this) {
                $groupe->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setIdUtilisateurEnvoie($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getIdUtilisateurEnvoie() === $this) {
                $message->setIdUtilisateurEnvoie(null);
            }
        }

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
            $commentaire->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUtilisateur() === $this) {
                $commentaire->setIdUtilisateur(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Amis>
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(Amis $utilisateur): static
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur->add($utilisateur);
            $utilisateur->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateur(Amis $utilisateur): static
    {
        if ($this->utilisateur->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getIdUtilisateur() === $this) {
                $utilisateur->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Amis>
     */
    public function getAmi(): Collection
    {
        return $this->ami;
    }

    public function addAmi(Amis $ami): static
    {
        if (!$this->ami->contains($ami)) {
            $this->ami->add($ami);
            $ami->setIdUtilisateurAmi($this);
        }

        return $this;
    }

    public function removeAmi(Amis $ami): static
    {
        if ($this->ami->removeElement($ami)) {
            // set the owning side to null (unless already changed)
            if ($ami->getIdUtilisateurAmi() === $this) {
                $ami->setIdUtilisateurAmi(null);
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
            $partage->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removePartage(Partage $partage): static
    {
        if ($this->partages->removeElement($partage)) {
            // set the owning side to null (unless already changed)
            if ($partage->getIdUtilisateur() === $this) {
                $partage->setIdUtilisateur(null);
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
            $enregistrer->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeEnregistrer(Enregistrer $enregistrer): static
    {
        if ($this->enregistrers->removeElement($enregistrer)) {
            // set the owning side to null (unless already changed)
            if ($enregistrer->getIdUtilisateur() === $this) {
                $enregistrer->setIdUtilisateur(null);
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
            $participer->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeParticiper(Participer $participer): static
    {
        if ($this->participers->removeElement($participer)) {
            // set the owning side to null (unless already changed)
            if ($participer->getIdUtilisateur() === $this) {
                $participer->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupesUtilisateurs>
     */
    public function getGroupesUtilisateurs(): Collection
    {
        return $this->groupesUtilisateurs;
    }

    public function addGroupesUtilisateur(GroupesUtilisateurs $groupesUtilisateur): static
    {
        if (!$this->groupesUtilisateurs->contains($groupesUtilisateur)) {
            $this->groupesUtilisateurs->add($groupesUtilisateur);
            $groupesUtilisateur->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeGroupesUtilisateur(GroupesUtilisateurs $groupesUtilisateur): static
    {
        if ($this->groupesUtilisateurs->removeElement($groupesUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($groupesUtilisateur->getIdUtilisateur() === $this) {
                $groupesUtilisateur->setIdUtilisateur(null);
            }
        }

        return $this;
    }
}
