<?php

declare(strict_types=1);

namespace App\Service;

use Twig\Environment;

class Navigator
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function preparePageNavigator(
        string $url,
        int $level,
        int $listLimit,
        int $count,
        int $levelLimit
    ): string {
        $pageNavigator = '';

        if ($count > $listLimit) {
            $minLevel = 1;
            $maxLevel = number_format($count / $listLimit, 0, '.', '');
            $number = number_format($count / $listLimit, 2, '.', '');
            $maxLevel = ($number > $maxLevel) ? $maxLevel + 1 : $maxLevel;
            $number = $level - $levelLimit;
            $fromLevel = ($number < $minLevel) ? $minLevel : $number;
            $number = $level + $levelLimit;
            $toLevel = ($number > $maxLevel) ? $maxLevel : $number;
            $previousLevel = $level - 1;
            $nextLevel = $level + 1;

            $pageNavigator = $this->twig->render(
                'navigator/_page_navigator.html.twig',
                [
                    'url' => $url,
                    'level' => $level,
                    'levelLimit' => $levelLimit,
                    'minLevel' => $minLevel,
                    'maxLevel' => $maxLevel,
                    'fromLevel' => $fromLevel,
                    'toLevel' => $toLevel,
                    'previousLevel' => $previousLevel,
                    'nextLevel' => $nextLevel
                ]
            );
        }

        return $pageNavigator;
    }
}
