<?php

namespace App\Controller;


use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * @Route("/customer", name ="customer_")
 */
class CustomerController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    //Retourne la liste des customers presents ds la base
    public function index(CustomerRepository $cr)
    {     
        $customers= $cr->findAll();
        
        return $this->render('customer/index.html.twig',[
            'customers' =>$customers
        ]);
    }
    
    /**
     * @Route("/create", name="create")
     */
    //création d'un customer ds la bdd
    public function create(EntityManagerInterface $em, Request $request){
        
        $customer = new Customer();

        $customer->setStatus('Test');

        $form = $this->createForm(CustomerType::class,$customer);

        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($customer);
            $em->flush();

            $this->addFlash(
                "success","Le client {$customer->getName()} a été ajouté avec succès"
            );

            return $this->redirectToRoute('customer_update',[
                'customerId'=>$customer->getId()    
            ]);
        }

        return $this->render('customer/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/{customerId}/update", name="update", requirements={"customerId"= "\d+"})
     */
    //mise a jour d'un customer par rapport a son id en passant par le formulaire de creation
    public function edit(EntityManagerInterface $em, Request $request, CustomerRepository $cr, $customerId){ 

        $customer = $cr->find($customerId);

        if(empty($customer)){ 

            $this->addFlash(
                "warning","Le client n'existe pas."
            );

            return $this->redirectToRoute('customer_list');
        }


        $form = $this->createForm(CustomerType::class,$customer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            
            $this->addFlash(
                "success","Le client {$customer->getName()} a été édité avec succès"
            );

            return $this->redirectToRoute('customer_update',[
                'customerId'=>$customerId
            ]);
        }

        return $this->render('customer/update.html.twig',[
            'form'=>$form->createView(),
            'customer'=>$customer
        ]);

    }

    /**
     * @Route("/{customerId}/delete", name="delete", requirements={"customerId"= "\d+"})
     */
    //supprime un customer par rapport a son id
    public function delete(EntityManagerInterface $em, CustomerRepository $cr, $customerId){
        
        $deleteCustomer = $cr->find($customerId);

        if(empty($deleteCustomer)){ 
            $this->addFlash(
                "warning","Le client n'existe pas."
            );
            return $this->redirectToRoute('customer_list');
        }

        try{
            $em->remove($deleteCustomer);
            $em->flush();
            
            $this->addFlash(
                "success","Le client {$deleteCustomer->getName()} a été supprimé avec succès"
            );
            return $this->redirectToRoute('customer_list');
        }catch(ForeignKeyConstraintViolationException $e){

            $this->addFlash(
                "warning","Le client ne peut etre supprimé. Il possède toujours des sites web"
            );

            return $this->redirectToRoute('customer_list'); 
        }
    }
   
}
