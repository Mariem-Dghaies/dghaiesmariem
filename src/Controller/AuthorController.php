<?php

namespace App\Controller;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinmaxType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/{name}', name: 'showauthor')]
    public function showauthor($name): Response
    {
        return $this->render('author/showother.html.twig', [
            'name' => $name
        ]);
}

#[Route('/showtableauthor', name: 'showtableauthor')]
    public function showtableauthor(): Response
    {
    
        return $this->render('author/table.html.twig', [
            'author' =>$this->authors ,
        ]);
}
#[Route('/showidauthor/{id}', name: 'showidauthor')]
    public function showidauthor($id): Response
    {
    //var_dump($id).die();
    $x=null;
    foreach($this->authors as $authord){
        if($authord['id']==$id){
            $x=$authord;
        }
    }
    //var_dump($x).die();
        return $this->render('author/showbyid.html.twig', [
            'authotr' => $x,
        ]);
}








#[Route('/showdbauthor', name: 'showdbauthor')]
public function showdbauthor(AuthorRepository $x ,Request $req): Response
{ 
    //$author =$x->findAll();
    $author=$x->trieemail();
   // $x->deleteAuthorsWithNoBooks();
    //$x->delete();
   //$this->addFlash('success', 'deleted.');
    $form=$this->createForm(MinmaxType::class);
    $form->handleRequest($req);
    if($form->isSubmitted() ){
       $min= $form->get('min')->getData();
       $max= $form->get('max')->getData();
       $authors=$x->minmax($min,$max);
       return $this->renderForm('author/showdbauthor.html.twig', [
        'author'=> $authors,
        'f'=> $form
    ]);
    }
    return $this->renderForm('author/showdbauthor.html.twig', [
        'f'=>$form,
        'author' =>$author
    ]);
}





#[Route('/addauthor', name: 'addauthor')]
public function addauthor(ManagerRegistry $m): Response
{ 
    $em=$m->getManager();//atini acce bach nistail ay foncion andi 
    $author=new Author();
    $author->setUsername("3a55");
    $author->setEmail("3a55@gmail.com");
    $em->persist($author);
    $em->flush();//flush bach texucity requet al baaththa
return new Response("great add");
}




#[Route('/addformauthor', name: 'addformauthor')]
public function addformauthor(ManagerRegistry $m ,Request $req): Response
{
    $em=$m->getManager();//atini acce bach nistail ay foncion andi 
    $author=new Author();

    $form=$this->createForm(AuthorType::class,$author);
    //handelrequest recuperer les data et request cet une methode post 
   $form->handleRequest($req);
    if ($form->isSubmitted() and $form ->isValid()){
    $em->persist($author);
    $em->flush();
    return $this->redirect('showdbauthor');
    }
    return $this->renderForm('author/addform.html.twig', [
    'f'=>$form    
]);
}
#[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor($id,ManagerRegistry $m,AuthorRepository $authorRepository ,Request $req): Response
    {
        //var_dump($id).die();
        $em=$m->getManager();
        $dataid=$authorRepository->find($id);
        //var_dump($dataid).die();
        $form=$this->createForm(AuthorType::class,$dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()){
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showdbauthor');
        }

        return $this->renderForm('author/editauthor.html.twig', [
        'form' =>$form  ]);
    }
    
    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteAuthor($id,ManagerRegistry $m, AuthorRepository $authorRepository,Request $req): Response
    {
        $em=$m->getManager();
        $id=$authorRepository->find($id);//njibou data ali hiya mawjouda f colonne aka
        $em->remove($id);
        $em->flush();// pour faire léxucution
        return $this->redirectToRoute('showdbauthor');
    }
}    