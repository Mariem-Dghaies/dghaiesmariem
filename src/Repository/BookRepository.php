<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
    
    public function searchref($ref)
    { return $this->createQueryBuilder('r') 
        ->where('r.ref=:Referance') 
        ->setParameter('Referance',$ref) 
        ->getQuery() 
        ->getResult(); }
     public function trie() 
     { return $this->createQueryBuilder('b') 
        ->leftJoin('b.author', 'author') 
        ->addOrderBy('author.username', 'ASC') 
        ->getQuery() 
        ->getResult(); } 
     public function findBooks()
     {
         return $this->createQueryBuilder('b')
             ->join('b.author', 'author') 
             ->andWhere('b.publicationdate < :date')
             ->andWhere('author.nbbook > 35') 
             ->setParameter('date', new \DateTime('2023-01-01'))
             ->getQuery()
             ->getResult();
     }
        public function updateBooks($authorName, $newCategory) {
             $qb = $this->createQueryBuilder('b') 
            ->join('b.author', 'a') 
            ->where('a.username = :authorName') 
            ->setParameter('authorName', $authorName); 
            $books = $qb->getQuery()->getResult(); 
            foreach ($books as $book) { $book->setCategory($newCategory); }
             $this->_em->flush(); }


         public function ScienceFiction()
          { return $this->createQueryBuilder('b') ->select('COUNT(b) as bookCount')
             ->where('b.category = :category') 
             ->setParameter('category', 'Science Fiction')
              ->getQuery() 
              ->getSingleScalarResult(); } 


         public function Published(): int { 
            return $this->createQueryBuilder('b') 
            ->select('COUNT(b)') 
            ->where('b.published = true') 
            ->getQuery() 
            ->getSingleScalarResult(); } 


         public function Unpublished(): int { 
            return $this->createQueryBuilder('b') 
            ->select('COUNT(b)') 
            ->where('b.published = false') 
            ->getQuery() 
            ->getSingleScalarResult(); } 


            public function Betweendate(\DateTimeInterface $startDate, \DateTimeInterface $endDate)
             { return $this->createQueryBuilder('b') 
                ->andWhere('b.publicationdate >= :start_date')
                 ->andWhere('b.publicationdate <= :end_date') 
                 ->setParameter('start_date', $startDate)
                  ->setParameter('end_date', $endDate) 
                  ->getQuery() 
                  ->getResult(); }
        

    }
//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

