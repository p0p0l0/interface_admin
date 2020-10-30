<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * @Route("/type", name="type_")
 */
class TypeController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(TypeRepository $tr)
    {
        return $this->render('type/index.html.twig', [
            'types' => $tr->findAll()
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(EntityManagerInterface $em, Request $request, TranslatorInterface $translator)
    {
        $type = new Type();

        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);
            $em->flush();

            $this->addFlash(
                "success",
                $type->getName() . $translator->trans(" added successfully ")
            );

            return $this->redirectToRoute('type_update', [
                'typeId' => $type->getId()
            ]);
        }

        return $this->render('type/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/{typeId}/update", name="update", requirements={"typeId"="\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, TypeRepository $tr, $typeId, TranslatorInterface $translator)
    {
        $type = $tr->find($typeId);

        if (!$type) {
            $this->addFlash(
                "warning",
                $translator->trans("The type doesn't exist")
            );

            return $this->redirectToRoute('type_list');
        }

        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash(
                "success",
                $type->getName() . $translator->trans(" edited successfully")
            );

            return $this->redirectToRoute('type_update', [
                'typeId' => $typeId
            ]);
        }

        return $this->render('type/create.html.twig', [
            'form' => $form->createView(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/{typeId}/delete", name="delete", requirements={"typeId"= "\d+"})
     */
    //supprime un customer par rapport a son id
    public function delete(EntityManagerInterface $em, TypeRepository $cr, $typeId, TranslatorInterface $translator)
    {

        $type = $cr->find($typeId);

        if (!$type) {

            $this->addFlash(
                "warning",
                $translator->trans("The type doesn't exist")
            );
            return $this->redirectToRoute('type_list');
        }
        try {

        $em->remove($type);
        $em->flush();

        $this->addFlash(
            "success",
            $type->getName() . $translator->trans(" deleted successfully")
        );
        return $this->redirectToRoute('type_list');
        } catch (ForeignKeyConstraintViolationException $e) {

            $this->addFlash(
                "warning",
                $type->getName() . $translator->trans(" cannot be deleted. Type still has websites")
            );

            return $this->redirectToRoute('type_list');
        }
   }
}
