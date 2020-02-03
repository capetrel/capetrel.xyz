<?php
namespace App\Framework\Twig;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class MinifyExtension extends \Twig_Extension
{

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('minify', [$this, 'minify']),
        ];
    }

    public function minify(string $sourcePath, string $minifiedPath): ?string
    {
        $extension = mb_strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        if ($extension === 'css') {
            $minifier = new CSS($sourcePath);
            return $minifier->minify($minifiedPath);
        } elseif ($extension === 'js') {
            $minifier = new JS($sourcePath);
            return $minifier->minify($minifiedPath);
        }
        return null;
    }
}