<?php
namespace App\Framework\Router;

use Framework\Router;

class RouterTwigExtension extends \Twig_Extension
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'asset']),
            new \Twig_SimpleFunction('path', [$this, 'pathFor']),
            new \Twig_SimpleFunction('is_subpath', [$this, 'isSubPath'])
        ];
    }

    public function asset(?string $path): ?string
    {
        if (is_null($path)) {
            return null;
        }
        $url = '/';
        return $url . $path;
    }

    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }

    public function isSubPath(string $path): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $expectedUri = $this->router->generateUri($path);
        return strpos($uri, $expectedUri) !== false;
    }
}
