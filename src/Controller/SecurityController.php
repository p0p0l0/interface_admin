<?php

namespace App\Controller;

use App\Form\SecurityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="security_login")
     */
    public function login()
    {
     return $this->render('security/login.html.twig');
    }

    /**
     * @Route ("/deconnexion", name= "security_logout")
     */
    public function logout(){}

}
