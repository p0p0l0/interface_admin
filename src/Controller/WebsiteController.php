<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(EntityManagerInterface $em, $userId,$clientId)
    {
        $client = $em->getRepository(Client::class)->find($clientId);
        $user = $em->getRepository(User::class)->find($userId);
        $websites = $em->getRepository(Website::class)->findAll();
        return $this->render('website/index.html.twig',[
            'user'=>$user,
            'client'=>$client
        ]);
    }
}
