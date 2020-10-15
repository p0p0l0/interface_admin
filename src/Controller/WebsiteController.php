<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Website;
use App\Form\WebsiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{userId}/{customerId}", name="website_", requirements = {"userId" = "\d+", "customerId" = "\d+"})
 */
class WebsiteController extends AbstractController
{
    /**
     * @Route("/website", name="list")
     */
    public function index(EntityManagerInterface $em, $userId, $customerId)
    {
        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user = $em->getRepository(User::class)->find($userId);
        $websites = $em->getRepository(Website::class)->findAll();

        return $this->render('website/index.html.twig',[
            'websites'=>$websites,
            'user'=>$user,
            'customer'=>$customer
        ]);
    }

    /**
     * @Route("/create", name="create", requirements = {"userId" = "\d+", "customerId" = "\d+"})
     */
    public function create(EntityManagerInterface $em, Request $request, $userId, $customerId ){
       
        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user = $em->getRepository(User::class)->find($userId); 

        $customer->setEditAt(new \Datetime())
               ->setUserEdit($user->getUsername());
               
        $website = new Website();
       
        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);
        
        $website->setCustomer($customer);
        $name = $website->getName();
            
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($website);
            $em->flush();

            $this->addFlash(
                "success","Le website $name de {$customer->getName()} a bien été ajouté "
            );

            return $this->redirectToRoute('website_list',[
                'userId'=>$userId,
                'customerId'=>$customerId
            ]);
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user,
            'customer'=>$customer

        ]);
    }


    /**
     * @Route("/{websiteId}/edit", name="edit", requirements={"userId" = "\d+", "customerId" = "\d+", "websiteId"="\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, $userId, $customerId, $websiteId){
        
        $website = $em->getRepository(Website::class)->find($websiteId);
        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user = $em->getRepository(User::class)->find($userId);

        $customer->setEditAt(new \DateTime())
                 ->setUserEdit($user->getUsername());

        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash(
                "success","Le site {$website->getName()} a bien été édité"
            );

            return $this->redirectToRoute('website_list',[
                'userId'=>$userId,
                'customerId'=>$customerId
            ]);
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user,
            'customer'=>$customer

        ]);
    }

    /**
     * @Route("/{websiteId}/delete", name="delete", requirements={"userId" = "\d+", "customerId" = "\d+", "websiteId"="\d+"})
     */
    public function delete(EntityManagerInterface $em, $userId, $customerId, $websiteId){
        $user = $em->getRepository(User::class)->find($userId);
        $customer =$em->getRepository(Customer::class)->find($customerId);
        $deletWebsite = $em->getRepository(Website::class)->find($websiteId);
        $name = $deletWebsite->getName();

        $customer->setEditAt(new \DateTime())
                 ->setUserEdit($user->getUsername());
            
        $em->remove($deletWebsite);
        $em->flush();

        $this->addFlash(
            "success","Le site $name a bien été supprimé"
        );

        return $this->redirectToRoute('website_list',[
            'userId'=>$userId,
            'customerId'=>$customerId
        ]);
    }
}
