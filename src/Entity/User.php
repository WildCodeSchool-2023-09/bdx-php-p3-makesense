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
#[UniqueEntity(fields: ['phoneNumber'], message: 'Ce numéro de téléphone existe déjà')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Le email ne doit pas être vide')]
    #[Assert\Email(message: 'Le email {{ value }} n\'ai pas valide.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le mail saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le Nom ne doit pas être vide')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Votre Nom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre Nom ne peut pas contenir plus de {{ limit }} caractères',
    )]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le prénom  ne doit pas être vide')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Votre prénom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre prénom ne peut pas contenir plus de {{ limit }} caractères',
    )]
    private ?string $firstname = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le numéro de téléphone ne doit pas être vide')]
    #[Assert\Regex(
        pattern: '/^\d{10}$/',
        message: 'Le numéro de téléphone doit être composé de 10 chiffres'
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'La ville ne doit pas être vide')]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La profession ne doit pas être vide')]
    private ?string $occupation = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reseau = null;

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

/*    #[ORM\ManyToMany(targetEntity: Decision::class, mappedBy: 'admin')]
    private Collection $decision;
*/
    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    private Collection $memberGroup;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Decision::class, mappedBy: 'users')]
    private Collection $decisions;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Opinion::class)]
    private Collection $opinions;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->memberGroup = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->opinions = new ArrayCollection();
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every admin at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

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
        // If you store any temporary, sensitive data on the admin, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(string $occupation): static
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReseau(): ?string
    {
        return $this->reseau;
    }

    public function setReseau(?string $reseau): static
    {
        $this->reseau = $reseau;

        return $this;
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
     * @param Decision $decision
     * @return User
     */
   /* public function getDecision(): Collection
    {
        return $this->decision;
    }*/

    public function addDecision(Decision $decision): static
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions->add($decision);
            //$decision->getAuthor($this);
        }
        return $this;
    }

    public function removeDecision(Decision $decision): static
    {
        if ($this->decisions->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getUsers() === $this) {
                $decision->getUsers(null);
            }
        }
        return $this;
    }

    /**
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
     * @return Collection<int, Decision>
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }

    /**
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
}
