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

    #[Route('/admin/books/par-prix/{min}/{max}', name:'app_admin_book_listByPrice')]
    public function listByPrice(BookRepository $bookRepository, float $min, float $max): Response{
        $books = $bookRepository->findByPriceBetween($min, $max);
        return $this->render('/admin/book/listByPrice.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/admin/books/par-prix-entre/{min}/{max}', name:'app_admin_book_listByPriceOrder')]
    public function listByPriceOrder(BookRepository $bookRepository, float $min, float $max): Response{
        $books = $bookRepository->findByPriceBetweenOrder($min, $max);
        return $this->render('/admin/book/listByPriceOrder.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/admin/books/par-auteur/{authorName}', name:'app_admin_book_listByAuthorName')]
    public function listByAuthorName(BookRepository $bookRepository, string $authorName): Response{
        $books = $bookRepository->findByAuthorName($authorName);
        return $this->render('/admin/book/listByAuthorName.html.twig', [
            'books' => $books,
            'author' => $authorName,
        ]);
    }

    #[Route('/admin/books/par-categorie/{nomCategory}', name:'app_admin_book_listByCategory')]
    public function listByCategory(BookRepository $bookRepository, string $nomCategory): Response{
        $books = $bookRepository->findByCategory($nomCategory);
        return $this->render('/admin/book/listByCategory.html.twig', [
            'books' => $books,
            'category' => $nomCategory,
        ]);
    }
}
