<?php
// src/Controller/ErrorController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    // Capture toutes les URLs problématiques avec doubles slashes
    #[Route('//{any}', name: 'app_double_slash_fix', requirements: ['any' => '.*'], priority: -10)]
    public function fixDoubleSlash(Request $request, string $any = ''): Response
    {
        $baseUrl = $request->getSchemeAndHttpHost();
        $cleanPath = '/' . ltrim($any, '/');
        $cleanUrl = $baseUrl . $cleanPath;
        
        // Log pour le débogage (optionnel)
        // error_log("Double slash detected: {$request->getUri()} -> {$cleanUrl}");
        
        return $this->redirect($cleanUrl, 301);
    }

    // Capture toutes les autres URLs non trouvées
    #[Route('/{any}', name: 'app_catch_all', requirements: ['any' => '.*'], priority: -1)]
    public function catchAll(Request $request, string $any = ''): Response
    {
        $currentUrl = $request->getUri();
        
        // Vérifier si l'URL contient des doubles slashes après le domaine
        $parsedUrl = parse_url($currentUrl);
        if (isset($parsedUrl['path']) && strpos($parsedUrl['path'], '//') !== false) {
            $cleanPath = preg_replace('#/+#', '/', $parsedUrl['path']);
            $cleanUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . 
                       (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '') . 
                       $cleanPath . 
                       (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '') . 
                       (isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '');
            
            return $this->redirect($cleanUrl, 301);
        }
        
        return $this->render('error/404.html.twig', [
            'current_url' => $currentUrl,
            'requested_path' => $any
        ], new Response('', 404));
    }
}