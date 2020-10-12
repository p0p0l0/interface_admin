<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterfaceController extends AbstractController
{
    /**
     * @Route("/interface", name="interface")
     */
    //Retourne la liste des clients presents ds la base
    public function index(EntityManagerInterface $em)
    {

        $clients= $em->getRepository(Client::class)->findAll();
        return $this->render('index.html.twig',[
            'clients' =>$clients
        ]);
    }


    /**
     * @Route("/interface/create", name="create")
     */
    //création d'un client ds la bdd
    public function create(EntityManagerInterface $em, Request $request){
        $client = new Client();
        $client->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($client);
            $em->flush();
        return $this->redirectToRoute('interface');
        }

        return $this->render('create.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/interface/edit/{clientId}", name="edit", requirements={"clientId"= "\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, $clientId ){

        $client = $em->getRepository(Client::class)->find($clientId);
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em->flush();

                return $this->redirectToRoute('interface');
            }

        return $this->render('create.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/interface/delete/{clientId}", name="delete", requirements={"clientId"= "\d+"})
     */
    public function delete(EntityManagerInterface $em, $clientId){
        
        $deleteClient = $em->getRepository(Client::class)->find($clientId);
        $em->remove($deleteClient);
        $em->flush();

        return $this->redirectToRoute('interface');
    }

   
}
