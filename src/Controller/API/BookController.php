<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Book;
use App\Form\API\BookType;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/api/books', name: 'app_api_book_list', methods: ['GET'])]
    public function list(BookRepository $repository): Response
    {
        $books = $repository->findAll();

        return $this->json($books);
    }

    #[Route('/api/books', name:'app_api_book_create', methods:['POST'])]
    public function create(Request $request, BookRepository $repository): Response{

        $form = $this->createForm(BookType::class)->handleRequest($request);
        // on crée et on remplit le formulaire 
        
        if( !$form->isSubmitted() || !$form->isValid()){
            // problème avec le formulaire 
            return $this->json($form->getErrors(true), 400);
        }
        // On ajoute dans la base de données
        $repository->add($form->getData());

        return $this->json($form->getData(), 201);

    }

    #[Route('/api/books/{id}', name:'app_api_book_get', methods:['GET'])]
    public function get(Book $book): Response{

        return $this->json($book);

    }

    #[Route('/api/books/{id}', name:'app_api_book_update', methods:['PUT', 'PATCH'])]
    public function update(Book $book, BookRepository $repository, Request $request): Response{

        $form = $this->createForm(BookType::class, $book, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isValid() || !$form->isSubmitted()){

            return $this->json($form->getErrors(true), 400);
        }

        $repository->add($form->getData());
        return $this->json($form->getData());

    }

    #[Route('/api/books/{id}', name:'app_api_book_delete', methods:['DELETE'])]
    public function delete(Book $book, BookRepository $repository): Response{
        $repository->remove($book);
        return $this->json($book);
    }
}