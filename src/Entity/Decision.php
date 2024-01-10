<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DecisionRepository::class)]
class Decision
{
    use DecisionDeadlines;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne doit pas être vide')]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'L\'impact ne doit pas être vide')]
    private ?string $impact = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contexte ne doit pas être vide')]
    private ?string $context = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'L\'avantages ne doit pas être vide')]
    private ?string $benefits = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le risque ne doit pas être vide')]
    private ?string $risk = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineDecision = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineConflict = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineFinal = null;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Opinion::class, orphanRemoval: true)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Favori::class, orphanRemoval: true)]
    private Collection $favoris;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'decision')]
    private ?Collection $memberGroups;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'decisions')]
    private ?Collection $users;
    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'decisions')]
    private ?Collection $groupes;

    public function __construct()
    {
        $this->opinions = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->memberGroups = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

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

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(string $impact): static
    {
        $this->impact = $impact;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getBenefits(): ?string
    {
        return $this->benefits;
    }

    public function setBenefits(string $benefits): static
    {
        $this->benefits = $benefits;

        return $this;
    }

    public function getRisk(): ?string
    {
        return $this->risk;
    }

    public function setRisk(string $risk): static
    {
        $this->risk = $risk;

        return $this;
    }

    public function getDeadlineDecision(): ?\DateTimeImmutable
    {
        return $this->deadlineDecision;
    }

    public function setDeadlineDecision(\DateTimeImmutable $deadlineDecision): static
    {
        $this->deadlineDecision = $deadlineDecision;

        return $this;
    }

    public function getDeadlineConflict(): ?\DateTimeImmutable
    {
        return $this->deadlineConflict;
    }

    public function setDeadlineConflict(\DateTimeImmutable $deadlineConflict): static
    {
        $this->deadlineConflict = $deadlineConflict;

        return $this;
    }

    public function getDeadlineFinal(): ?\DateTimeImmutable
    {
        return $this->deadlineFinal;
    }

    public function setDeadlineFinal(\DateTimeImmutable $deadlineFinal): static
    {
        $this->deadlineFinal = $deadlineFinal;

        return $this;
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
            $opinion->setDecision($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): static
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getDecision() === $this) {
                $opinion->setDecision(null);
            }
        }

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
            $favori->setDecision($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getDecision() === $this) {
                $favori->setDecision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getMemberGroups(): Collection
    {
        return $this->memberGroups;
    }

    public function addMemberGroup(Group $memberGroup): static
    {
        if (!$this->memberGroups->contains($memberGroup)) {
            $this->memberGroups->add($memberGroup);
            $memberGroup->addDecision($this);
        }

        return $this;
    }

    public function removeMemberGroup(Group $memberGroup): static
    {
        if ($this->memberGroups->removeElement($memberGroup)) {
            $memberGroup->removeDecision($this);
        }

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
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Group $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function removeGroupe(Group $groupe): static
    {
        $this->groupes->removeElement($groupe);

        return $this;
    }
}
