<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Website;
use App\Entity\Customer;
use App\Form\WebsiteType;
use App\Repository\TypeRepository;
use App\Repository\WebsiteRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/website", name="website_")
 */
class WebsiteController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(EntityManagerInterface $em, WebsiteRepository $wr, TranslatorInterface $translator)
    {

        $websites = $wr->findAll();

        return $this->render('website/index.html.twig',[
            'websites'=>$websites
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(EntityManagerInterface $em, Request $request, TranslatorInterface $translator ){
       
               
        $website = new Website();
       
        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($website);
            $em->flush();

            $this->addFlash(
                "success",$website->getServerName().$translator->trans(" added successfully")
            );

            return $this->redirectToRoute('website_list');
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/{websiteId}/update", name="update", requirements={"websiteId"="\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, WebsiteRepository $wr, 
                        $websiteId, TranslatorInterface $translator){

        $website = $wr->findBy($websiteId);
        
        if(!$website){ 
            $this->addFlash(
                "warning",$translator->trans(" The website doesn't exist")
            );
            return $this->redirectToRoute('website_list');
        }


        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

           $this->addFlash(
               "success", $website->getServerName().$translator->trans( "edited succesfully")
           );

            return $this->redirectToRoute('website_list');
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/{websiteId}/delete", name="delete", requirements={"customerId" = "\d+", "websiteId"="\d+"})
     */
    public function delete(EntityManagerInterface $em, CustomerRepository $cr, $customerId, WebsiteRepository $wr,
                           $websiteId, TranslatorInterface $translator){
    
        $customer = $cr->find($customerId);

        if(empty($customer)){ 
            $this->addFlash(
                "warning",$translator->trans("The customer doesn't exist")
            );
            return $this->redirectToRoute('customer_list');
        }

        $deleteWebsite = $wr->findOneBy([
            'customer'=>$customer,
            'id'=>$websiteId
            ]);

        if(empty($deleteWebsite)){ 
            $this->addFlash(
                "warning",$translator->trans("The website doesn't exist")
            );
            return $this->redirectToRoute('website_list',[
                'customerId'=>$customerId
            ]);
        }

            
        $em->remove($deleteWebsite);
        $em->flush();

        $this->addFlash(
            "success",$deleteWebsite->getServerName().$translator->trans(" deleted successfully")
        );

        return $this->redirectToRoute('website_list',[
            'customerId'=>$customerId
        ]);
    }
}
