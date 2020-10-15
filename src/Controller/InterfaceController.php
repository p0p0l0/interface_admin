<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * @Route("/{userId}", name = "interface_", requirements = {"userId" = "\d+"})
 */
class InterfaceController extends AbstractController
{
    /**
     * @Route("/interface", name="list")
     */
    //Retourne la liste des clients presents ds la base
    public function index(EntityManagerInterface $em,$userId)
    {     
        $clients= $em->getRepository(Client::class)->findAll();
        $user = $em->getRepository(User::class)->find($userId);
        return $this->render('interface/index.html.twig',[
            'clients' =>$clients,
            'user'=> $user
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    //création d'un client ds la bdd
    public function create(EntityManagerInterface $em, Request $request, $userId){
        
        $client = new Client();
    
        $user = $em->getRepository(User::class)->find($userId);

        $client->setCreatedAt(new \DateTime())
               ->setUserCreation($user->getUsername());
                

        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);

        $name = $client->getName();
    
            if($form->isSubmitted() && $form->isValid()){
                $em->persist($client);
                $em->flush();

                $this->addFlash(
                    "success","Le client $name a été ajouté avec succès"
                );

            return $this->redirectToRoute('interface_list',[
                'userId'=>$userId
            ]);
            }

        return $this->render('interface/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }
    /**
     * @Route("/{clientId}/edit", name="edit", requirements={"clientId"= "\d+"})
     */
    //mise a jour d'un client par rapport a son id en passant par le formulaire de creation
    public function edit(EntityManagerInterface $em, Request $request, $userId, $clientId ){ 

        $client = $em->getRepository(Client::class)->find($clientId);
        $user = $em->getRepository(User::class)->find($userId);

        $client->setEditAt(new \Datetime())
               ->setUserEdit($user->getUsername());
               
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);

        $name = $client->getName();

            if($form->isSubmitted() && $form->isValid()){
                $em->flush();
                
                $this->addFlash(
                    "success","Le client $name a été édité avec succès"
                );

                return $this->redirectToRoute('interface_list',[
                    'userId'=>$userId
                ]);
            }

        return $this->render('interface/create.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);

    }

    /**
     * @Route("/{clientId}/delete", name="delete", requirements={"clientId"= "\d+"})
     */
    //supprime un client par rapport a son id
    public function delete(EntityManagerInterface $em, $userId, $clientId){
        
        $deleteClient = $em->getRepository(Client::class)->find($clientId);

        $name = $deleteClient->getName();

        try{
            $em->remove($deleteClient);
            $em->flush();
            
            $this->addFlash(
                "success","Le client $name a été supprimé avec succès"
            );
            return $this->redirectToRoute('interface_list',[
                'userId'=>$userId
            ]);
        }catch(ForeignKeyConstraintViolationException $e){

            $this->addFlash(
                "warning","Le client ne peut etre supprimé. Il possède toujours des sites web"
            );

            
        }
    }
   
}
