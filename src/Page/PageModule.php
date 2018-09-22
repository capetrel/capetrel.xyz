<?php
namespace App\Page;

use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use App\Page\Action\PageCrudAction;
use Framework\Router;
use Psr\Container\ContainerInterface;

class PageModule extends Module
{
    const DEFINITIONS = __DIR__.'/config.php';

    const NAME = 'page';

    public function __construct(ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('page', __DIR__ . '/views');
        $router = $container->get(Router::class);

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/page", PageCrudAction::class, "page.admin");
        }
    }
}