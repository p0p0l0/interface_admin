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


class CustomerController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('customer/home.html.twig');
    }
    /**
     * @Route("/customer/list", name="customer_list")
     */
    //Retourne la liste des customers presents ds la base
    public function list(EntityManagerInterface $em)
    {     
        $customers= $em->getRepository (Customer::class)->findAll();
        $user = $this->getUser();
        return $this->render('customer/list.html.twig',[
            'customers' =>$customers,
            'user'=> $user
        ]);
    }

    /**
     * @Route("/create", name="customer_create")
     */
    //création d'un customer ds la bdd
    public function create(EntityManagerInterface $em, Request $request, $userId){
        
        $customer = new Customer();
    
        $user = $em->getRepository(User::class)->find($userId);

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

            return $this->redirectToRoute('customer_list',[
                'userId'=>$userId
            ]);
            }

        return $this->render('customer/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }
    /**
     * @Route("/{customerId}/edit", name="customer_edit", requirements={"customerId"= "\d+"})
     */
    //mise a jour d'un customer par rapport a son id en passant par le formulaire de creation
    public function edit(EntityManagerInterface $em, Request $request, $userId, $customerId){ 

        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user = $em->getRepository(User::class)->find($userId);

        $customer->setEditAt(new \Datetime())
               ->setUserEdit($user->getUsername());
               
        $form = $this->createForm(CustomerType::class,$customer);
        $form->handleRequest($request);

        $name = $customer->getName();

            if($form->isSubmitted() && $form->isValid()){
                $em->flush();
                
                $this->addFlash(
                    "success","Le customer $name a été édité avec succès"
                );

                return $this->redirectToRoute('customer_list',[
                    'userId'=>$userId
                ]);
            }

        return $this->render('customer/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);

    }

    /**
     * @Route("/{customerId}/delete", name="customer_delete", requirements={"customerId"= "\d+"})
     */
    //supprime un customer par rapport a son id
    public function delete(EntityManagerInterface $em, $userId, $customerId){
        
        $deleteClient = $em->getRepository (Customer::class)->find($customerId);

        $name = $deleteClient->getName();

        try{
            $em->remove($deleteClient);
            $em->flush();
            
            $this->addFlash(
                "success","Le customer $name a été supprimé avec succès"
            );
            return $this->redirectToRoute('customer_list',[
                'userId'=>$userId
            ]);
        }catch(ForeignKeyConstraintViolationException $e){

            $this->addFlash(
                "warning","Le customer ne peut etre supprimé. Il possède toujours des sites web"
            );

            
        }
    }
   
}
