<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }


    public function trieemail()
    {
        return $this->createQueryBuilder('au')
        ->orderBy('au.email','ASC')
        ->getQuery()
        ->getResult();

    }
    public function minmax($min,$max){
        $em=$this->getEntityManager();
        return $em->createQuery('SELECT a from App\Entity\Author a where a.nbbook BETWEEN :min AND :max') 
        ->setParameters(['min' => $min, 'max' => $max])
        ->getResult();
    }
    /* public function deleteAuthorsWithNoBooks()
    {
        $qb = $this->createQueryBuilder('a')
        ->where('a.nbbook = 0')
        ->delete()
        ->getQuery();

    $qb->execute();
    } */

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
