<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/admin/books', name:'app_admin_book_list', methods:['GET'])]   
    public function list(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('/admin/book/list.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/admin/books/nouveau', name:'app_admin_book_create', methods:['GET', 'POST'])]
    public function create(Request $request, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, new Book)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $bookRepository->add($form->getData());
            return $this->redirectToRoute('app_admin_book_list');

        }
        return $this->render('/admin/book/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/books/{id}/modifier', name:'app_admin_book_update', methods:['GET', 'POST'])]
    public function update(Book $book, Request $request, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, $book)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $bookRepository->add($form->getData());
            return $this->redirectToRoute('app_admin_book_list');

        }
        return $this->render('/admin/book/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/books/{id}/supprimer', name:'app_admin_book_delete', methods:['GET'])]
    public function delete(Book $book, BookRepository $bookRepository): Response
    {
        $bookRepository->remove($book);
        return $this->redirectToRoute('app_admin_book_list');
    }
}
