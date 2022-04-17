<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Category;
use App\Form\API\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'app_api_category_list', methods: ['GET'])]
    public function list(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->json($categories);
    }

    #[Route('/api/categories', name:'app_api_category_create', methods:['POST'])]
    public function create(Request $request, CategoryRepository $repository): Response{

        $form = $this->createForm(CategoryType::class)->handleRequest($request);
        // on crée et on remplit le formulaire 
        
        if( !$form->isSubmitted() || !$form->isValid()){
            // problème avec le formulaire 
            return $this->json($form->getErrors(true), 400);
        }
        // On ajoute dans la base de données
        $repository->add($form->getData());

        return $this->json($form->getData(), 201);

    }

    #[Route('/api/categories/{id}', name:'app_api_category_get', methods:['GET'])]
    public function get(Category $category): Response{

        return $this->json($category);

    }

    #[Route('/api/categories/{id}', name:'app_api_category_update', methods:['PUT', 'PATCH'])]
    public function update(Category $category, CategoryRepository $repository, Request $request): Response{

        $form = $this->createForm(CategoryType::class, $category, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isValid() || !$form->isSubmitted()){

            return $this->json($form->getErrors(true), 400);
        }

        $repository->add($form->getData());
        return $this->json($form->getData());

    }

    #[Route('/api/categories/{id}', name:'app_api_category_delete', methods:['DELETE'])]
    public function delete(Category $category, CategoryRepository $repository): Response{
        $repository->remove($category);

        return $this->json($category);

    }
}