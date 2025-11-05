<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class ThemeService
{
    private RequestStack $requestStack;
    private const THEME_COOKIE = 'app_theme';
    private const DEFAULT_THEME = 'dark';

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCurrentTheme(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return self::DEFAULT_THEME;
        }

        // Priorité : paramètre GET > cookie > session > défaut
        $theme = $request->query->get('theme');
        if ($theme && in_array($theme, ['light', 'dark', 'auto'])) {
            return $theme;
        }

        $theme = $request->cookies->get(self::THEME_COOKIE, self::DEFAULT_THEME);

        return in_array($theme, ['light', 'dark', 'auto']) ? $theme : self::DEFAULT_THEME;
    }

    public function isDarkMode(): bool
    {
        $theme = $this->getCurrentTheme();

        if ($theme === 'auto') {
            $request = $this->requestStack->getCurrentRequest();
            if (!$request) {
                return true;
            }

            // Détection via les préférences système
            $prefersDark = $request->headers->get('Sec-CH-Prefers-Color-Scheme') === 'dark' ||
                          str_contains($request->headers->get('User-Agent', ''), 'dark') ||
                          $this->detectSystemTheme();

            return $prefersDark;
        }

        return $theme === 'dark';
    }

    private function detectSystemTheme(): bool
    {
        // Cette détection sera complétée par JavaScript côté client
        return false;
    }

    public function getThemeClasses(): string
    {
        $theme = $this->getCurrentTheme();
        $classes = ['theme-' . $theme];

        if ($this->isDarkMode()) {
            $classes[] = 'dark-mode';
        } else {
            $classes[] = 'light-mode';
        }

        return implode(' ', $classes);
    }

    public function getOppositeTheme(): string
    {
        return $this->isDarkMode() ? 'light' : 'dark';
    }
}
