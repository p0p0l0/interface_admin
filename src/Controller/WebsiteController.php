<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Website;
use App\Form\WebsiteType;
use App\Repository\CustomerRepository;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/customer/{customerId}/website", name="website_", requirements = {"customerId" = "\d+"})
 */
class WebsiteController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(EntityManagerInterface $em, CustomerRepository $cr,WebsiteRepository $wr, $customerId)
    {
        $customer = $cr->find($customerId);
        $websites = $wr->findAll();

        return $this->render('website/index.html.twig',[
            'websites'=>$websites,
            'customer'=>$customer
        ]);
    }

    /**
     * @Route("/create", name="create", requirements = {"customerId" = "\d+"})
     */
    public function create(EntityManagerInterface $em, CustomerRepository $cr, Request $request, $customerId ){
       
        $customer = $cr->find($customerId);

        $customer->setEditAt(new \Datetime())
                 ->setUserEdit($this->getUser()->getUsername());
               
        $website = new Website();
       
        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);
        
        $website->setCustomer($customer);
            
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($website);
            $em->flush();

            $this->addFlash(
                "success","Le website {$website->getName()} de {$customer->getName()} a bien été ajouté "
            );

            return $this->redirectToRoute('website_list',[
                'customerId'=>$customerId
            ]);
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView(),
            'customer'=>$customer
        ]);
    }


    /**
     * @Route("/{websiteId}/update", name="update", requirements={"customerId" = "\d+", "websiteId"="\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, WebsiteRepository $wr, 
                        CustomerRepository $cr, $customerId, $websiteId){
        
        $website = $wr->find($websiteId);
        $customer = $cr->find($customerId);

        $customer->setEditAt(new \DateTime())
                 ->setUserEdit($this->getUser()->getUsername());

        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash(
                "success","Le site {$website->getName()} a bien été édité"
            );

            return $this->redirectToRoute('website_list',[
                'customerId'=>$customerId
            ]);
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView(),
            'customer'=>$customer

        ]);
    }

    /**
     * @Route("/{websiteId}/delete", name="delete", requirements={"customerId" = "\d+", "websiteId"="\d+"})
     */
    public function delete(EntityManagerInterface $em, CustomerRepository $cr, $customerId, WebsiteRepository $wr,
                           $websiteId){
    
        $customer = $cr->find($customerId);
        $deletWebsite = $wr->find($websiteId);

        $customer->setEditAt(new \DateTime())
                 ->setUserEdit($this->getUser()->getUsername());
            
        $em->remove($deletWebsite);
        $em->flush();

        $this->addFlash(
            "success","Le site {$deletWebsite->getName()} a bien été supprimé"
        );

        return $this->redirectToRoute('website_list',[
            'customerId'=>$customerId
        ]);
    }
}
