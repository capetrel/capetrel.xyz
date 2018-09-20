<?php
namespace App\Admin;

use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Renderer\TwigRenderer;
use Framework\Router;

class AdminModule extends Module
{

    const DEFINITIONS = __DIR__.DIRECTORY_SEPARATOR.'config.php';

    public const NAME = 'admin';

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        AdminTwigExtension $adminTwigExtension,
        string $prefix
    ) {
        $renderer->addPath('admin', __DIR__ . '/views');
        $router->get($prefix, DashboardAction::class, 'admin');
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($adminTwigExtension);
        }
    }
}
