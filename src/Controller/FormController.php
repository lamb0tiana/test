<?php

namespace App\Controller;

use App\Entity\Anwser;
use App\Entity\Field;
use App\Entity\Form as Model;
use App\Form\FormType;
use App\Repository\AnwserRepository;
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
        /**
         * I won't do the pagination query, the goal is not that for this test.
         */
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

    #[Route('/pages/{slug}', name: 'view_form')]
    public function view(#[MapEntity(mapping: ['slug' => 'slug'])] ?Model $form, AnwserRepository $repository)
    {
        if (!$form) {
            return $this->redirectToRoute('home');
        }
        $dd = $repository->getAnwsers($form);
        dump($dd);
        return $this->render('form/view.html.twig', ['form' => $form]);
    }


    /**
     * Persist the answers given by the user for a specific form identified by the slug.
     *
     * @param Model|null $model the form to which the answers are related
     * @param Request $request the request containing the answers
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response to redirect to home page
     *
     * @throws NotFoundHttpException if a field with the given id is not found
     */
    #[Route('/answers/{slug}', name: 'answer_form', methods: [Request::METHOD_POST])]
    public function persistAnwser(?Model $model, Request $request, EntityManagerInterface $entityManager): Response
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
