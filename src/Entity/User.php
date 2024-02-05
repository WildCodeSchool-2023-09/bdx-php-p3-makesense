<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Ce email existe déjà')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UserProfilTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'L\'email ne doit pas être vide')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'L\'email saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères',
    )]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[Vich\UploadableField(mapping: 'user_photo', fileNameProperty: 'photo')]
    #[Assert\File(
        maxSize: '4M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $photoFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favori::class)]
    private Collection $favoris;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    private Collection $memberGroup;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Opinion::class)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Decision::class)]
    private Collection $decisionOwner;

    #[ORM\ManyToMany(targetEntity: Decision::class, mappedBy: 'userExpert')]
//    #[ORM\JoinTable(name : 'decision_expert')]
    private Collection $expertDecision;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->memberGroup = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->decisionOwner = new ArrayCollection();
        $this->expertDecision = new ArrayCollection();
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
     * A visual identifier that represents this admin.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
        // If you store any temporary, sensitive data on the admin, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Favori>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favori $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setUser($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getUser() === $this) {
                $favori->setUser(null);
            }
        }

        return $this;
    }

    /**
<<<<<<< Updated upstream
=======
     * @param Decision $decision
     * @return User
     */


    /**
>>>>>>> Stashed changes
     * @return Collection<int, Group>
     */
    public function getMemberGroup(): Collection
    {
        return $this->memberGroup;
    }

    public function addMemberGroup(Group $memberGroup): static
    {
        if (!$this->memberGroup->contains($memberGroup)) {
            $this->memberGroup->add($memberGroup);
        }

        return $this;
    }

    public function removeMemberGroup(Group $memberGroup): static
    {
        $this->memberGroup->removeElement($memberGroup);

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
<<<<<<< Updated upstream
=======
     * @return Collection<int, Decision>
     */


    /**
>>>>>>> Stashed changes
     * @return Collection<int, Opinion>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): static
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setAuthor($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): static
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getAuthor() === $this) {
                $opinion->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?DatetimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DatetimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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

    public function setPhotoFile(?File $photoFile = null): User
    {
        $this->photoFile = $photoFile;
        if ($this->photoFile instanceof UploadedFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    // Resolution message erreur : Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed

// Ce message d'erreur indique que la sérialisation de cet objet n'est pas autorisée.
// La sérialisation est le processus de conversion d'un objet en une représentation de chaîne de caractères,
// généralement dans le but de stocker cet objet dans une base de données,
// de le transmettre via un réseau ou de le sauvegarder d'une manière ou d'une autre.
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'password' => $this->password,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'phoneNumber' => $this->phoneNumber,
            'city' => $this->city,
            'occupation' => $this->occupation,
            'description' => $this->description,
            'reseau' => $this->reseau,
            'photo' => $this->photo,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->roles = $data['roles'] ?? [];
        $this->password = $data['password'] ?? null;
        $this->firstname = $data['firstname'] ?? null;
        $this->lastname = $data['lastname'] ?? null;
        $this->phoneNumber = $data['phoneNumber'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->occupation = $data['occupation'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->reseau = $data['reseau'] ?? null;
        $this->photo = $data['photo'] ?? null;
    }
    /**
     * @return Collection<int, Decision>
     */
    public function getDecisionOwner(): Collection
    {
        return $this->decisionOwner;
    }

    public function addDecisionOwner(Decision $decisionOwner): static
    {
        if (!$this->decisionOwner->contains($decisionOwner)) {
            $this->decisionOwner->add($decisionOwner);
            $decisionOwner->setOwner($this);
        }

        return $this;
    }

    public function removeDecisionOwner(Decision $decisionOwner): static
    {
        if ($this->decisionOwner->removeElement($decisionOwner)) {
            // set the owning side to null (unless already changed)
            if ($decisionOwner->getOwner() === $this) {
                $decisionOwner->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Decision>
     */
    public function getExpertDecision(): Collection
    {
        return $this->expertDecision;
    }

    public function addExpertDecision(Decision $expertDecision): static
    {
        if (!$this->expertDecision->contains($expertDecision)) {
            $this->expertDecision->add($expertDecision);
            $expertDecision->addUserExpert($this);
        }

        return $this;
    }

    public function removeExpertDecision(Decision $expertDecision): static
    {
        if ($this->expertDecision->removeElement($expertDecision)) {
            $expertDecision->removeUserExpert($this);
        }

        return $this;
    }
}
