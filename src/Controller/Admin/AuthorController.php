<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/admin/auteurs', name:'app_admin_author_list', methods:['GET'])]
    public function list(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();
        return $this->render("/admin/author/list.html.twig", [
            'authors' => $authors,
        ]);
    }

    #[Route('/admin/auteurs/nouveau', name:'app_admin_author_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author;
        $form = $this->createForm(AuthorType::class, $author)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $author->setCreatedAt(new DateTime);
            $author->setUpdatedAt(new DateTime);
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_author_list');
        }
        return $this->render("/admin/author/create.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/auteurs/{id}/modifier', name:'app_admin_author_update', methods:['GET', 'POST'])]
    public function update(Author $author, Request $request, AuthorRepository $repository): Response
    {
        $form = $this->createForm(AuthorType::class, $author)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $author->setUpdatedAt(new DateTime);
            //$entityManager->persist($author);
            //$entityManager->flush();
            $repository->add($form->getData());
            return $this->redirectToRoute('app_admin_author_list');
        }

        return $this->render('/admin/author/update.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    #[Route('/admin/auteurs/{id}/supprimer', name:'app_admin_author_delete', methods:['GET'])]
    public function delete(Author $author, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($author);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_author_list');
    }
}
