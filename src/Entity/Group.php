<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'memberGroup')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Decision::class, inversedBy: 'memberGroups')]
    private Collection $decision;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->decision = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addMemberGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeMemberGroup($this);
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
        }

        return $this;
    }

    public function removeDecision(Decision $decision): static
    {
        $this->decision->removeElement($decision);

        return $this;
    }
}
