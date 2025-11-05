<?php
namespace App\Controller;

use App\Form\SearchType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
        // 🔧 Injection du repository dans le constructeur
        public function __construct(LivreRepository $livreRepository)
        {
            $this->livreRepository = $livreRepository;
        }
    #[Route('/', name: 'app_home')]
        public function index(Request $request): Response
        {
            $form = $this->createForm(SearchType::class);
            $form->handleRequest($request);
            $livres = null;
            if ($form->isSubmitted() && $form->isValid()) {
                $query = $form->get('q')->getData();
                //$livres = $this->livreRepository->searchByKeyword($query);
                $this->livreRepository->searchByKeyword($query);
                return $this->redirectToRoute('app_home', ['q' => $query]);
            }

            if ($request->query->get('q')) {
                $query = $request->query->get('q');
                $livres = $this->livreRepository->searchByKeyword($query);
            }

            return $this->render('accueil/index.html.twig', [
                'form' => $form->createView(),
                'livres' => $livres,
            ]);
        }
}
