<?php

namespace App\Repository;

use App\Data\SearchOffre;
use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    // /**
    //  * @return Offre[] Returns an array of Offre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offre
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Récupère les produits en lien avec une recherche
     * @return Offre[]
     */
   public function findSearch(SearchOffre $search): array
    {

       $query = $this
            ->createQueryBuilder('o')
            ->select('s', 'o')
            ->andWhere('o.publier = true')
            ->join('o.souscategorie', 's')
             ->orderBy('o.createdAt', 'DESC');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('o.description LIKE :q')
                ->setParameter('q', "%{$search->q}%") ;
        }

          if (!empty($search->categories)) {
            $query = $query
                ->andWhere('s.id IN (:categories)')
                ->setParameter('categories', $search->categories) ;

        }

        if (!empty($search->ville)) {

            $query = $query
                ->andWhere('o.lieu LIKE :ville')
                ->setParameter('ville', "%{$search->ville}%")  ;

        }

        return $query->getQuery()
                     ->getResult()
                    ;

    }
}
