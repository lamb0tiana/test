<?php

namespace App\Controller;

use App\Entity\Form as Model;
use App\Form\FormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        return $this->json(['test']);
    }
    #[Route('/form', name: 'app_form')]
    public function form(Request $request, EntityManagerInterface $entityManager): Response
    {
        $model = new Model();
        $form = $this->createForm(FormType::class, $model);
        if($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($model);
                $entityManager->flush();

                return $this->redirectToRoute('home');
            }
        }
        return $this->render('form/create.html.twig', [
            'controller_name' => 'FormController',
            'form' => $form->createView(),
        ]);
    }
}
