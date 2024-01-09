<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

trait DecisionDeadlines
{
    #[ORM\Column]
    private ?\DateTimeImmutable $startingDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $deadlineOpinion = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineDecision = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineConflict = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineFinal = null;

    public function getStartingDate(): ?\DateTimeImmutable
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeImmutable $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getDeadlineOpinion(): ?\DateTimeImmutable
    {
        return $this->deadlineOpinion;
    }

    public function setDeadlineOpinion(\DateTimeImmutable $deadlineOpinion): static
    {
        $this->deadlineOpinion = $deadlineOpinion;

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
}
