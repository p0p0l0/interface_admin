<?php

namespace App\Controller;

use App\Entity\User;

use App\Entity\Customer;
use App\Form\CustomerType;
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
    public function index(EntityManagerInterface $em)
    {     
        $customers= $em->getRepository (Customer::class)->findAll();
        
        return $this->render('customer/index.html.twig',[
            'customers' =>$customers,
            'user'=>$this->getUser()
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    //création d'un customer ds la bdd
    public function create(EntityManagerInterface $em, Request $request){
        
        $customer = new Customer();
    
        $user = $this->getUser();

        $customer->setCreatedAt(new \DateTime())
                 ->setUserCreation($user->getUsername());
                

        $form = $this->createForm(CustomerType::class,$customer);
        $form->handleRequest($request);

        $name = $customer->getName();
    
                if($form->isSubmitted() && $form->isValid()){
                $em->persist($customer);
                $em->flush();

                $this->addFlash(
                    "success","Le customer $name a été ajouté avec succès"
                );

            return $this->redirectToRoute('customer_list');
            }

        return $this->render('customer/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }
    /**
     * @Route("/{customerId}/update", name="update", requirements={"customerId"= "\d+"})
     */
    //mise a jour d'un customer par rapport a son id en passant par le formulaire de creation
    public function edit(EntityManagerInterface $em, Request $request, $customerId){ 

        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user = $this->getUser();

        $customer->setEditAt(new \Datetime())
                 ->setUserEdit($user->getUsername());
               
        $form = $this->createForm(CustomerType::class,$customer);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $em->flush();
                
                $this->addFlash(
                    "success","Le customer {$customer->getName()} a été édité avec succès"
                );

                return $this->redirectToRoute('customer_list');
            }

        return $this->render('customer/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);

    }

    /**
     * @Route("/{customerId}/delete", name="delete", requirements={"customerId"= "\d+"})
     */
    //supprime un customer par rapport a son id
    public function delete(EntityManagerInterface $em, $customerId){
        
        $deleteClient = $em->getRepository (Customer::class)->find($customerId);

        $name = $deleteClient->getName();

        try{
            $em->remove($deleteClient);
            $em->flush();
            
            $this->addFlash(
                "success","Le customer $name a été supprimé avec succès"
            );
            return $this->redirectToRoute('customer_list');
        }catch(ForeignKeyConstraintViolationException $e){

            $this->addFlash(
                "warning","Le customer ne peut etre supprimé. Il possède toujours des sites web"
            );

            
        }
    }
   
}
