<?php

namespace App\Controller;

use App\Entity\Categrie;
use App\Form\CategrieType;
use App\Repository\CategrieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categrie')]
final class CategrieController extends AbstractController
{
    #[Route(name: 'app_categrie_index', methods: ['GET'])]
    public function index(CategrieRepository $categrieRepository): Response
    {
        return $this->render('categrie/index.html.twig', [
            'categries' => $categrieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categrie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categrie = new Categrie();
        $form = $this->createForm(CategrieType::class, $categrie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categrie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categrie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categrie/new.html.twig', [
            'categrie' => $categrie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categrie_show', methods: ['GET'])]
    public function show(Categrie $categrie): Response
    {
        return $this->render('categrie/show.html.twig', [
            'categrie' => $categrie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categrie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categrie $categrie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategrieType::class, $categrie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categrie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categrie/edit.html.twig', [
            'categrie' => $categrie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categrie_delete', methods: ['POST'])]
    public function delete(Request $request, Categrie $categrie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categrie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categrie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categrie_index', [], Response::HTTP_SEE_OTHER);
    }
}
