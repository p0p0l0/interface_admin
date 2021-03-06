<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Website;
use App\Entity\Customer;
use App\Form\WebsiteType;
use App\Repository\TypeRepository;
use App\Repository\WebsiteRepository;
use App\Repository\CustomerRepository;
use App\Service\WebsiteService;
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

        return $this->render('website/index.html.twig', [
            'websites' => $websites
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(EntityManagerInterface $em, Request $request, TranslatorInterface $translator, WebsiteRepository $wr, WebsiteService $ws)
    {


        $website = new Website();

        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            if(empty($website->getCustomer())){
                $this->addFlash(
                    "warning",
                    "A customer must be selected"
                );
                return $this->redirectToRoute('website_create');
            }

            $verifType = $wr->findOneBy(['customer' => $website->getCustomer(), 'type' => $website->getType()]);
            if (isset($verifType)) {
                $this->addFlash(
                    "warning",
                    $translator->trans("The customer cannot have such a website")
                );
                return $this->redirectToRoute('website_list');
            }
            
            $em->persist($website);
            $em->flush();
            
            $ws->createWebsite($website);
            
            $this->addFlash(
                "success",
                $website->getServerName() . $translator->trans(" added successfully")
            );

            return $this->redirectToRoute('website_update',[
                'websiteId'=>$website->getId()
            ]);
        }

        return $this->render('website/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{websiteId}/update", name="update", requirements={"websiteId"="\d+"})
     */
    public function edit(
        EntityManagerInterface $em,
        Request $request,
        WebsiteRepository $wr,
        $websiteId,
        TranslatorInterface $translator,
        WebsiteService $ws
    ) 
    {


        $website = $wr->find($websiteId);
        if (!$website) {
            $this->addFlash(
                "warning",
                $translator->trans(" The website doesn't exist")
            );
            return $this->redirectToRoute('website_list');
        }
        $nameFolderBefore = $website->getNameFolder();
        $verifCustomer = $website->getCustomer();
        $verifType = $website->getType();

        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($website->getType() != $verifType) {
                $this->addFlash(
                    "warning",
                    $translator->trans("The customer cannot have such a website")
                );
                return $this->redirectToRoute('website_update', [
                    'websiteId' => $websiteId
                ]);
            }
        
            if ($verifCustomer != $website->getCustomer()) {
                $this->addFlash(
                    "warning",
                    $translator->trans("The customer cannot be changed")
                );
                return $this->redirectToRoute('website_update', [
                    'websiteId' => $websiteId
                ]);
            }

            $em->flush();

            $ws->updateWebsite($website,$nameFolderBefore);

            $this->addFlash(
                "success",
                $website->getServerName() . $translator->trans(" edited succesfully")
            );

            return $this->redirectToRoute('website_update',[
                'websiteId'=>$websiteId
            ]);
        }

        return $this->render('website/create.html.twig', [
            'form' => $form->createView(),
            'website'=>$website
        ]);
    }

    /**
     * @Route("/{websiteId}/delete", name="delete", requirements={"websiteId"="\d+"})
     */
    public function delete(
        EntityManagerInterface $em,
        WebsiteRepository $wr,
        $websiteId,
        TranslatorInterface $translator,
        WebsiteService $ws
    )
    {

        $website = $wr->find($websiteId);
    
        if (!$website) {
            $this->addFlash(
                "warning",
                $translator->trans("The website doesn't exist")
            );
            return $this->redirectToRoute('website_list');
        }

        /*if ($website->getStatus()=='Inactive') {
            $this->addFlash(
                "warning",
                $translator->trans("The website is not yet active")
            );
            return $this->redirectToRoute('website_list');
        }*/

        $em->remove($website);
        $em->flush();

        $ws->deleteWebsite($website);
        
        $this->addFlash(
            "success",
            $website->getServerName() . $translator->trans(" deleted successfully")
        );

        return $this->redirectToRoute('website_list');
   
    }

    /**
     * @Route("/{websiteId}/maj", name="maj", requirements = {"websiteId"="\d+"})
     */
    public function maj(EntityManagerInterface $em,
                        WebsiteRepository $wr,
                        $websiteId,
                        TranslatorInterface $translator,
                        WebsiteService $ws)
    {

        $website = $wr->find($websiteId);

        if (!$website) {
            $this->addFlash(
                "warning",
                $translator->trans("The website doesn't exist")
            );
            return $this->redirectToRoute('website_list');
        }

        $ws->majWebsite($website);

        $this->addFlash(
            "success",
            $website->getServerName() . $translator->trans(" maj successfully")
        );

        return $this->redirectToRoute('website_update',[
            'websiteId'=>$websiteId
        ]);

    }
}
