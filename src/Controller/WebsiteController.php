<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Website;
use App\Form\WebsiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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


    /**
     * @Route("/{websiteId}/edit", name="edit", requirements={"userId" = "\d+", "clientId" = "\d+", "websiteId"="\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, $userId, $clientId, $websiteId){
        
        $website = $em->getRepository(Website::class)->find($websiteId);
        $client = $em->getRepository(Client::class)->find($clientId);
        $user = $em->getRepository(User::class)->find($userId);

        $client->setEditAt(new \DateTime())
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
                'clientId'=>$clientId
            ]);
        }

        return $this->render('website/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user,
            'client'=>$client

        ]);
    }

    /**
     * @Route("/{websiteId}/delete", name="delete", requirements={"userId" = "\d+", "clientId" = "\d+", "websiteId"="\d+"})
     */
    public function delete(EntityManagerInterface $em, $userId, $clientId, $websiteId){
        $user = $em->getRepository(User::class)->find($userId);
        $client =$em->getRepository(Client::class)->find($clientId);
        $deletWebsite = $em->getRepository(Website::class)->find($websiteId);
        $name = $deletWebsite->getName();

        $client->setEditAt(new \DateTime())
               ->setUserEdit($user->getUsername());
            
        $em->remove($deletWebsite);
        $em->flush();

        $this->addFlash(
            "success","Le site $name a bien été supprimé"
        );

        return $this->redirectToRoute('website_list',[
            'userId'=>$userId,
            'clientId'=>$clientId
        ]);
    }
}
