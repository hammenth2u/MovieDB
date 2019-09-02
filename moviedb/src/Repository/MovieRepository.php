<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * S02E09 - EXO 1
     * Récupérer la liste des films par ordre alphabétique
     * Ici avec le query builder
     * 
     * @return Movie[] Returns an array of Movie objects
     */
    public function findAllOrderedByTitle()
    {
        // On obtient un objet query builder (qb) sur lequel on peut appliquer toutes les méthodes d'un qb
        // Ici on n'applique que orderBy()
        $query = $this->createQueryBuilder('m')
                      ->orderBy('m.title', 'ASC'); // ou alors ->add('orderby', 'm.title ASC')

        // $query = $this->createQueryBuilder('m');
        // $query->orderBy('m.title', 'ASC'); 

        // dump($query->getQuery()->getSingleResult());exit;

        return $query->getQuery()->getResult();
    }

    /**
     * S02E09 - EXO 1
     * Récupérer la liste des films par ordre alphabétique
     * Ici en DQL
     * 
     * @return Movie[] Returns an array of Movie objects
     */
    public function findAllOrderedByTitleDQL()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT m
                FROM App\Entity\Movie m
                ORDER BY m.title ASC
            ')
            ->getResult();
    } 
  
}
