<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['phoneNumber'], message: 'Ce numéro de téléphone existe déjà')]
trait UserProfilTrait
{
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

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'La ville ne doit pas être vide')]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La profession ne doit pas être vide')]
    private ?string $occupation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reseau = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le numéro de téléphone ne doit pas être vide')]
    #[Assert\Regex(
        pattern: '/^\d{10}$/',
        message: 'Le numéro de téléphone doit être composé de 10 chiffres'
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    private ?string $description = null;

    #[ORM\Column]
    private array $roles = [];

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
}
