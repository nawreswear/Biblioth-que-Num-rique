<?php

namespace App\Controller;

use App\Entity\Ouvrier;
use App\Form\OuvrierType;
use App\Repository\OuvrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/ouvrier')]
final class OuvrierController extends AbstractController
{
     /*LINK - #[Route('/calculer',name: 'app_ouvrier_calculer')]
     public function calculer(): Response
     { $x=5+2;
         return new Response('<h1>Resultat:'.$x.'</h1>');
     }*/
    /*#[Route('/calculer/$x/$y',name: 'app_ouvrier_calculer')]
     public function calculer(): Response
     { $y=2;
         $x=5;
        $z=$y+$x;
         return new Response('<h1>Resultat:'.$x.'</h1>');
     }*/

    #[Route('/new', name: 'app_ouvrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ouvrier = new Ouvrier();
        $form = $this->createForm(OuvrierType::class, $ouvrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ouvrier);
            $entityManager->flush();

            return $this->redirectToRoute('app_ouvrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ouvrier/new.html.twig', [
            'ouvrier' => $ouvrier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ouvrier_show', methods: ['GET'])]
    public function show(Ouvrier $ouvrier): Response
    {
        return $this->render('ouvrier/show.html.twig', [
            'ouvrier' => $ouvrier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ouvrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ouvrier $ouvrier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OuvrierType::class, $ouvrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ouvrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ouvrier/edit.html.twig', [
            'ouvrier' => $ouvrier,
            'form' => $form,
        ]);
    }


      #[Route('/calculer/{x}/{y}',name: 'app_ouvrier_calculer')]
     public function calculer(int $x=5, int $y=0): Response
     {
        $z=$y+$x;
         return new Response('<h1>Resultat:'.$x.'</h1>');
     }
       #[Route('/calculer/{x}/{y}',name: 'app_ouvrier_calculer')]
     public function somme(int $x=5, int $y=0): Response
     {
        $z=$y+$x;
         return new Response('<h1>Resultat:'.$x.'</h1>');
     }
    #[Route(name: 'app_ouvrier_index', methods: ['GET'])]
    public function index(OuvrierRepository $ouvrierRepository): Response
    {
        return $this->render('ouvrier/index.html.twig', [
            'ouvriers' => $ouvrierRepository->findAll(),
        ]);
    }
  #[Route('/{id}', name: 'app_ouvrier_delete', methods: ['POST'])]
    public function delete(Request $request, Ouvrier $ouvrier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ouvrier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ouvrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ouvrier_index', [], Response::HTTP_SEE_OTHER);
    }
}
