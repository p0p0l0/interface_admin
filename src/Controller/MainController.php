<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        if (empty($request->headers->get('referer'))) {
            return $this->redirectToRoute('security_login');
        } else {
            $request->getSession()->set('_locale', $locale);

            return $this->redirect($request->headers->get('referer'));
        }
    }
}
