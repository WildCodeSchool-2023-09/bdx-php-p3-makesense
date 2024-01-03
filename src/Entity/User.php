<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Ce email existe déjà')]
#[UniqueEntity(fields: ['phoneNumber'], message: 'Ce numéro de téléphone existe déjà')]
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
    #[Assert\NotBlank(message: 'Le mot de passe ne doit pas être vide')]
    #[Assert\Length(
        min: 8,
        max: 100,
        minMessage: 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre mot de passe ne peut pas contenir plus de {{ limit }} caractères',
    )]
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
    private ?int $phoneNumber = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'La ville ne doit pas être vide')]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'occupation ne doit pas être vide')]
    private ?string $occupation = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twitter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favori::class)]
    private Collection $favoris;

    #[ORM\ManyToMany(mappedBy: 'user', targetEntity: Decision::class)]
    private Collection $decision;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    private Collection $memberGroup;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->decision = new ArrayCollection();
        $this->memberGroup = new ArrayCollection();
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
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
        // If you store any temporary, sensitive data on the user, clear it here
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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): static
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): static
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

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
     * @return Collection<int, Decision>
     */
    public function getDecision(): Collection
    {
        return $this->decision;
    }

    public function addDecision(Decision $decision): static
    {
        if (!$this->decision->contains($decision)) {
            $this->decision->add($decision);
            $decision->setUser($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): static
    {
        if ($this->decision->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getUser() === $this) {
                $decision->setUser(null);
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
}
