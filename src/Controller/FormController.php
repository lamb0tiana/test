<?php

namespace App\Controller;

use App\Entity\Form;
use App\Form\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function index(): Response
    {
        $form = new Form();
        $forms = $this->createForm(FormType::class, $form);
        return $this->render('form/index.html.twig', [
            'controller_name' => 'FormController',
            'form' => $forms->createView(),
        ]);
    }
}
