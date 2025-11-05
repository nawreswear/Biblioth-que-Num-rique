<?php

namespace App\Controller;

use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    #[Route('/theme/toggle', name: 'app_theme_toggle')]
    public function toggle(Request $request, ThemeService $themeService): Response
    {
        $currentTheme = $themeService->getCurrentTheme();
        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';

        $response = $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));

        // Set cookie for 1 year
        $cookie = Cookie::create('app_theme', $newTheme, time() + 365 * 24 * 60 * 60);
        $response->headers->setCookie($cookie);

        return $response;
    }

    #[Route('/theme/set/{theme}', name: 'app_theme_set')]
    public function setTheme(string $theme, Request $request, ThemeService $themeService): Response
    {
        if (!in_array($theme, ['light', 'dark', 'auto'])) {
            $theme = 'dark';
        }

        $response = $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));

        $cookie = Cookie::create('app_theme', $theme, time() + 365 * 24 * 60 * 60);
        $response->headers->setCookie($cookie);

        return $response;
    }

    #[Route('/theme/current', name: 'app_theme_current')]
    public function getCurrentTheme(ThemeService $themeService): JsonResponse
    {
        return $this->json([
            'theme' => $themeService->getCurrentTheme(),
            'isDark' => $themeService->isDarkMode()
        ]);
    }
}
