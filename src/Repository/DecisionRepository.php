<?php

namespace App\Repository;

use App\Entity\Decision;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Decision>
 *
 * @method Decision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decision[]    findAll()
 * @method Decision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decision::class);
    }


    public function findDecisionsForUser(User $user): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.users', 'u')
            ->leftJoin('d.userExpert', 'ue')
            ->leftJoin('d.groupes', 'g')
            ->andWhere('d.owner = :user OR u = :user OR ue = :user OR :user MEMBER OF g.users')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
    /**
     * Retourne toutes les décisions triées par ordre décroissant de la date de début.
     *
     * @return Decision[]
     */
    public function findAllOrderedByDeadlineDecisionDesc(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.deadlineDecision', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
