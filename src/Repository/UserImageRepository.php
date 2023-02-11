<?php

namespace App\Repository;


use App\Entity\UserImage;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method UserImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserImage[]    findAll()
 * @method UserImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserImage::class);
    }

         /**
        * @return UserImage[] Returns an array of UserImage objects
       */
    
    public function findByimage($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->orderBy('u.updatedAt','DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
   

    /*
    public function findOneBySomeField($value): ?UserImage
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
     
   
}
