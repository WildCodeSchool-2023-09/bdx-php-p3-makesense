<?php

namespace App\Form\ClassForType;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\CallbackTransformer;

class CollectionArrayTransform extends CallbackTransformer
{
    public function __construct()
    {
        parent::__construct(
            function (Collection $usersAsCollection): array {
                return $usersAsCollection->toArray();
            },
            function (array $usersAsArray): Collection {
                return new ArrayCollection($usersAsArray);
            }
        );
    }
}
