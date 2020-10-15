<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Website;
use App\Form\WebsiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{userId}/{clientId}", name="website_", requirements = {"userId" = "\d+", "clientId" = "\d+"})
 */
class WebsiteController extends AbstractController
{
    /**
     * @Route("/website", name="list")
     */
    public function index(EntityManagerInterface $em, $userId, $clientId)
    {
        $client = $em->getRepository(Client::class)->find($clientId);
        $user = $em->getRepository(User::class)->find($userId);
        $websites = $em->getRepository(Website::class)->findAll();

        return $this->render('website/index.html.twig',[
            'websites'=>$websites,
            'user'=>$user,
            'client'=>$client
        ]);
    }

    /**
     * @Route("/create", name="create", requirements = {"userId" = "\d+", "clientId" = "\d+"})
     */
    public function create(EntityManagerInterface $em, Request $request, $userId, $clientId ){
       
        $client = $em->getRepository(Client::class)->find($clientId);
        $user = $em->getRepository(User::class)->find($userId); 

        $client->setEditAt(new \Datetime())
               ->setUserEdit($user->getUsername());
               
        $website = new Website();
       
        $form = $this->createForm(WebsiteType::class,$website);
        $form->handleRequest($request);
        
        $website->setClient($client);
        $name = $website->getName();
            
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($website);
            $em->flush();

            $this->addFlash(
                "success","Le website $name de {$client->getName()} a bien été ajouté "
            );

            return $this->redirectToRoute('website_list',[
                'userId'=>$userId,
                'clientId'=>$clientId
            ]);
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user,
            'client'=>$client

        ]);
    }
}
