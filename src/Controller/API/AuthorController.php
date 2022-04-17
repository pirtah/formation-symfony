<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\DTO\AuthorSearchCriteria;
use App\Entity\Author;
use App\Form\API\AuthorType;
use App\Form\AuthorSearchType;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    #[Route('/api/authors', name: 'app_api_author_list', methods: ['GET'])]
    public function list(AuthorRepository $repository, Request $request): Response
    {
        // réutiliser le formulaire de recherche d'auteurs créé précedemment
        $form = $this->createForm(AuthorSearchType::class, new AuthorSearchCriteria());
        $form->handleRequest($request);
        $criterias = $form->isSubmitted()&& $form->isValid()
            ? $form->getData()
            : new AuthorSearchCriteria();

        $authors = $repository->findByCriteria($criterias);
        return $this->json($authors);
    }

    #[Route('/api/authors', name:'app_api_author_create', methods:['POST'])]
    public function create(Request $request, AuthorRepository $repository): Response
    {

        $form = $this->createForm(AuthorType::class)->handleRequest($request);
        // on crée et on remplit le formulaire 
        
        if( !$form->isSubmitted() || !$form->isValid()){
            // problème avec le formulaire 
            return $this->json($form->getErrors(true), 400);
        }
        // On ajoute dans la base de données
        $repository->add($form->getData());

        return $this->json($form->getData(), 201);
    }

    #[Route('/api/authors/{id}', name:'app_api_author_get', methods:['GET'])]
    public function get(Author $author): Response
    {

        return $this->json($author);
    }

    #[Route('/api/authors/{id}', name:'app_api_author_update', methods:['PUT', 'PATCH'])]
    public function update(Author $author, AuthorRepository $repository, Request $request): Response
    {

        $form = $this->createForm(AuthorType::class, $author, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isValid() || !$form->isSubmitted()){

            return $this->json($form->getErrors(true), 400);
        }

        $repository->add($form->getData());
        return $this->json($form->getData());

    }

    #[Route('/api/authors/{id}', name:'app_api_author_delete', methods:['DELETE'])]
    public function delete(Author $author, AuthorRepository $repository): Response
    {
        $repository->remove($author);
        return $this->json($author);
    }
}