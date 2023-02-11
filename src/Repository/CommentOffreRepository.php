<?php

namespace App\Repository;

use App\Entity\CommentOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentOffre[]    findAll()
 * @method CommentOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentOffre::class);
    }

    // /**
    //  * @return CommentOffre[] Returns an array of CommentOffre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentOffre
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
