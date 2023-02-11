<?php

namespace App\Repository;

use App\Entity\Demande;
use App\Entity\SousCategorie;
use App\Data\SearchData;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    // /**
    //  * @return Demande[] Returns an array of Demande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demande
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Récupère les produits en lien avec une recherche
     * @return Demande[]
     */
   public function findSearch(SearchData $search): array
    {

       $query = $this
            ->createQueryBuilder('d')
            ->select('s', 'd')
            ->andWhere('d.publier = true')
            ->join('d.souscategorie', 's')
             ->orderBy('d.createdAt', 'DESC');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('d.titre LIKE :q')
                ->setParameter('q', "%{$search->q}%") ;
        }

          if (!empty($search->categories)) {
            $query = $query
                ->andWhere('s.id IN (:categories)')
                ->setParameter('categories', $search->categories) ;

        }

        if (!empty($search->ville)) {

            $query = $query
                ->andWhere('d.lieu LIKE :ville')
                ->setParameter('ville', "%{$search->ville}%")  ;

        }

        return $query->getQuery()
                     ->getResult()
                    ;

    }

    
}
