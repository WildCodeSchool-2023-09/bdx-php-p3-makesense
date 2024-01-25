<?php

namespace App\Service;

use App\Entity\Decision;
use DateTimeImmutable;

class DecisionDateService
{
    public const STEP_START_DECISION = 1;
    public const STEP_AVIS = 2;
    public const STEP_DECISION_MAKING = 3;
    public const STEP_CONFLICT = 4;
    public const STEP_DECISION_FINAL = 5;
    public const STEP_AFTER_EVENT = 6;
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
}
