<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findLikeName(string $name): array
    {
        // Crée un objet QueryBuilder associé à l'entité "g" (cela représente l'entité "Group").
        $queryBuilder = $this->createQueryBuilder('g')

            ->andWhere('g.name LIKE :name')
            // Associe la valeur de :name à la variable $name fournie en tant qu argument.
            // Notez l'utilisation de placeholders avec les deux pourcentages (%), ce qui
            //signifie que le terme peut être n'importe où dans le titre.
            ->setParameter('name', '%' . $name . '%')
            // Récupère la requête SQL construite jusqu'à présent.
            ->getQuery();

        // Exécute la requête et retourne les résultats.
        return $queryBuilder->getResult();
    }

//    /**
//     * @return Group[] Returns an array of Group objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Group
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
