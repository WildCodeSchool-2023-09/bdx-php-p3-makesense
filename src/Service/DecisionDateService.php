<?php

namespace App\Service;

use App\Entity\Decision;
use DateTimeImmutable;
use App\Repository\OpinionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DecisionDateService
{
    /**
     * @phpstan-ignore-next-line
     */
    private EntityManagerInterface $entityManager;

    /**
     * @phpstan-ignore-next-line
     */
    private OpinionRepository $opinionRepository;
    public const STEP_START_DECISION = 1;
    public const STEP_AVIS = 2;
    public const STEP_DECISION_MAKING = 3;
    public const STEP_CONFLICT = 4;
    public const STEP_DECISION_FINAL = 5;
    public const STEP_AFTER_EVENT = 6;

    public function __construct(EntityManagerInterface $entityManager, OpinionRepository $opinionRepository)
    {
        $this->entityManager = $entityManager;
        $this->opinionRepository = $opinionRepository;
    }
    public function getCurrentStep(Decision $decision): int
    {
        $currentDate = new DateTimeImmutable();

        if ($currentDate == $decision->getStartingDate()) {
            return self::STEP_START_DECISION;
        } elseif ($currentDate <= $decision->getDeadlineOpinion()) {
            return self::STEP_AVIS;
        } elseif ($currentDate <= $decision->getDeadlineDecision()) {
            return self::STEP_DECISION_MAKING;
        } elseif ($currentDate <= $decision->getDeadlineConflict()) {
            return self::STEP_CONFLICT;
        } elseif ($currentDate <= $decision->getDeadlineFinal()) {
            return self::STEP_DECISION_FINAL;
        } else {
            return self::STEP_AFTER_EVENT;
        }
    }
    public function isInOpinionInterval(Decision $decision, int $step): bool
    {
        $currentDate = new DateTimeImmutable();

        // Ajoutez la logique pour vérifier si la date actuelle est dans l'intervalle de l'avis pour l'étape spécifiée
        return match ($step) {
            2 => $currentDate >= $decision->getStartingDate() && $currentDate <= $decision->getDeadlineOpinion(),
            4 => $currentDate >= $decision->getDeadlineDecision()
                && $currentDate <= $decision->getDeadlineConflict(),
            default => false,
        };
    }

    public function getCurrentStepOpinions(Decision $decision, OpinionRepository $opinionRepository): array
    {
        $now = new DateTimeImmutable();
        $currentStepStart = $this->getCurrentStepStartDate($decision, $now);

        // Récupère les opinions pour la période actuelle
        $opinionsCurrentStep = [];
        $opinions = $opinionRepository->findAllOrderedByCreatedAtDesc();
        foreach ($opinions as $opinion) {
            if ($opinion->getCreatedAt() > $currentStepStart) {
                $opinionsCurrentStep[] = $opinion;
            }
        }

        return $opinionsCurrentStep;
    }
    public function getPreviousStepOpinions(
        Decision $decision,
        OpinionRepository $opinionRepository,
        int $currentStep
    ): array {
        $previousStepStart = null;
        if ($currentStep == self::STEP_AVIS) {
            $previousStepStart = $decision->getStartingDate();
        } elseif ($currentStep == self::STEP_DECISION_MAKING) {
            $previousStepStart = $decision->getDeadlineOpinion();
        } elseif ($currentStep == self::STEP_CONFLICT) {
            $previousStepStart = $decision->getDeadlineDecision();
        }

        // Récupère des opinions pour la période précédente
        $previousStepOpinions = $opinionRepository->findBy([
            'decision' => $decision,
            'createdAt' => $previousStepStart,
        ]);

        return $previousStepOpinions;
    }

    private function getCurrentStepStartDate(Decision $decision, DateTimeImmutable $now): DateTimeImmutable
    {
        if ($decision->getStartingDate() < $now && $now < $decision->getDeadlineOpinion()) {
            return $decision->getStartingDate();
        } elseif ($decision->getDeadlineOpinion() < $now && $now < $decision->getDeadlineDecision()) {
            return $decision->getDeadlineOpinion();
        } elseif ($decision->getDeadlineDecision() < $now && $now < $decision->getDeadlineConflict()) {
            return $decision->getDeadlineDecision();
        } elseif ($decision->getDeadlineConflict() < $now && $now < $decision->getDeadlineFinal()) {
            return $decision->getDeadlineConflict();
        }

        // Retourne la date de début par défaut si aucune période n'est trouvée
        return $decision->getStartingDate();
    }
}
