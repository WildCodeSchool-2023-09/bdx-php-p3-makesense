<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
