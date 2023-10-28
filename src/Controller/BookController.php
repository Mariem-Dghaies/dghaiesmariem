<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/showbook', name: 'showbook')]
    public function showbook(BookRepository $r,Request $req): Response
    {    
            
        $Published = $r->Published();      
        $unPublished = $r->Unpublished();

        $total= $r->ScienceFiction();

         $startDate = new \DateTime('2014-01-01');    
         $endDate = new \DateTime('2018-12-31');      
         $between = $r->Betweendate($startDate, $endDate);
             
      $form=$this->createForm(SearchType::class);       
       $form->handleRequest($req);        
       if($form->isSubmitted()){           
         $data=$form->get('ref')->getData();            
         $Ref=$r-> searchref($data);           
         return $this->renderForm('book/showbook.html.twig', [               
             'book'=> $Ref,  
                          
              'f'=> $form,
            'Published'=>$Published,           
             'unPublished'=>$unPublished,

             'total'=>$total,        
               'startDate' => $startDate,    
             'endDate' => $endDate,             
            'books'=>$between,              
        
            ]);  }      
         $booktri=$r->trie();  
          $find=$r->findBooks();
          $authorName = 'William Shakespear';      
          $newCategory = 'Romance';      
            $r->updateBooks($authorName, $newCategory); 
         
        return $this->renderForm('book/showbook.html.twig', [   
            'f'=> $form,           
            'book'=> $booktri,  
            'book'=>$find, 
                          
            'Published'=>$Published ,          
            'unPublished'=>$unPublished,                
            'total'=>$total,        
                                          
            'books'=>$between,           
            'startDate' => $startDate,  
            'endDate' => $endDate,             
        
        ]);}



    
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $ma ,Request $req): Response
    {
    $em=$ma->getManager();
        $book=new Book();
       $form = $this->createForm(BookType::class, $book);
      $form->handleRequest($req);

       if($form->isSubmitted() && $form->isValid()){
      $em->persist($book);
      $em->flush();
      return $this->redirectToRoute('showbook');
       }
        return $this->renderform('book/addbook.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/edit/{ref}', name: 'edit')]
    public function edit($ref,ManagerRegistry $ma ,BookRepository $b, Request $req): Response
    {
         $em=$ma->getManager();
         $reff=$b->find($ref);
       $form = $this->createForm(BookType::class, $reff);
      $form->handleRequest($req);

       if($form->isSubmitted() && $form->isValid()){
        $em->persist($reff);
        $em->flush();
        return $this->redirectToRoute('showbook');
       }
     

        return $this->renderform('book/editbook.html.twig', [
            'f' => $form
        ]);
    }


    #[Route('/delete/{ref}', name: 'delete')]
    public function delete($ref,ManagerRegistry $ma ,BookRepository $b, Request $req): Response
    {
         $em=$ma->getManager();
         $reff=$b->find($ref);
       
        $em->remove($reff);
        $em->flush();
        return $this->redirectToRoute('showbook');
       
     

    
    }
}
