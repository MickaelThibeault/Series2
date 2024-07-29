<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries()
    {
//        $entityManager = $this->getEntityManager();

//        $dql = '
//            SELECT s
//            FROM App\Entity\Serie s
//            WHERE s.popularity > 100
//            AND s.vote >8
//            ORDER BY s.popularity DESC
//        ';

//        $query = $entityManager->createQuery($dql);

        $querybuider = $this->createQueryBuilder('s');

        $querybuider->leftJoin('s.seasons', 'seas')
                    ->addSelect('seas')
                    ->andWhere('s.popularity > 100')
                    ->andWhere('s.vote > 8')
                    ->orderBy('s.popularity', 'DESC');
        $query = $querybuider->getQuery();

        $query->setMaxResults(50);
        $paginator = new Paginator($query);

//        $results = $query->getResult();
//
//        return $results;

        return $paginator;
    }

    //    /**
    //     * @return Serie[] Returns an array of Serie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Serie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
