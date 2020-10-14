<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            return $this->redirectToRoute('interface_list',[
                'userId'=> $userId
            ]);
            }
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig',[
            'error'=> $error
        ]);
    }

    /**
     * @Route ("/deconnexion", name= "security_logout")
     */
    public function logout(){}

}
