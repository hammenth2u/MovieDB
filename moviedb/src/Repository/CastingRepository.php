<?php

namespace App\Repository;

use App\Entity\Casting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    /**
     * S02E09 - EXO 2
     * Récupérer les castings d'un movie donné + les infos de Person
     * Méthode DQL
     * 
     * @param Movie $movie
     * @return Casting[]
     */
    public function findByMovieDQL($movie)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT c, p 
            FROM App\Entity\Casting c
            JOIN c.person p
            WHERE c.movie = :movie
        ')
        ->setParameter('movie', $movie);
        return $query->getResult(); 
    }

     /**
     * S02E09 - EXO 2
     * Récupérer les castings d'un movie donné + les infos de Person
     * Méthode avec le Query Builder
     * 
     * @param Movie $movie
     * @return Casting[]
     */
    public function findByMovie($movie)
    {
        $qb = $this->createQueryBuilder('c')
        ->addSelect('p')
        ->join('c.person', 'p')
        ->where('c.movie = :myMovie')
        ->setParameter('myMovie', $movie)
        ;
    
        //cast retour de requete
        return $qb->getQuery()->getResult();
    }
}
