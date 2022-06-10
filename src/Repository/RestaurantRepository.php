<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository implements RestaurantRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, $symfonyEnv)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function findAll()
    {
        return parent::findAll();
    }

    public function findOneById($id)
    {
        return parent::findOneBy(['id' => $id]);
    }

    public function findOneByName($name)
    {
        return parent::findOneBy(['name' => $name]);
    }
    
    public function findOneByAddress($address)
    {
        return parent::findOneBy(['address' => $address]);
    }

    public function findOneByLikes($likes)
    {
        return parent::findOneBy(['likes' => $likes]);
    }

    public function findOneByDislikes($dislikes)
    {
        return parent::findOneBy(['dislikes' => $dislikes]);
    }
    

    // /**
    //  * @return Restaurant[] Returns an array of Restaurant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Restaurant
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
