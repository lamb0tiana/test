<?php

namespace App\Controller;

use App\Entity\Anwser;
use App\Entity\Field;
use App\Entity\Form as Model;
use App\Form\FormType;
use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(FormRepository $formRepository): Response
    {
        //I do not write pagination
        $forms = $formRepository->findAll();
        return $this->render('form/listing.html.twig', ['listForms' => $forms]);
    }

    #[Route('/page/create', name: 'create_form')]
    public function form(Request $request, EntityManagerInterface $entityManager): Response
    {
        $model = new Model();
        $form = $this->createForm(FormType::class, $model);
        if ($request->isMethod(Request::METHOD_POST)) {
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

    #[Route('/page/{slug}', name: 'view_form')]
    public function view(#[MapEntity(mapping: ['slug' => 'slug'])] ?Model $form)
    {
        dump($form->getFields()->current()->getAnwsers()->toArray());
        return !$form ? $this->redirectToRoute('home') : $this->render('form/view.html.twig', ['form' => $form]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/answer/{slug}', name: 'answer_form', methods: [Request::METHOD_POST])]
    public function persistAnwser(#[MapEntity(mapping: ['slug' => 'slug'])] ?Model $model, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$model) {
            return $this->redirectToRoute('home');
        }

        $posts = $request->request->all();
        $fieldRepository = $entityManager->getRepository(Field::class);

        foreach ($posts as $key => $value) {
            $fieldId = str_replace('field_', '', $key);
            $field = $fieldRepository->find($fieldId);

            if (!$field) {
                throw $this->createNotFoundException("Field with id {$fieldId} not found");
            }

            $anwser = new Anwser();
            $anwser->setField($field);
            $anwser->setValue($value);

            $entityManager->persist($anwser);
        }

        $entityManager->flush();
        $this->addFlash('success', true);
        return $this->redirectToRoute('home');
    }
}
