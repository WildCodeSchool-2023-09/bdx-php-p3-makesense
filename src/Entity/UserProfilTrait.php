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
}
