<?php
namespace App\Home;

use App\Framework\Actions\PageCrudAction;
use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use App\Home\Action\HomeAction;
use Framework\Router;
use Psr\Container\ContainerInterface;

class HomeModule extends Module
{
    const DEFINITIONS = __DIR__.'/config.php';

    const MIGRATIONS = __DIR__.'/db/migrations/';

    const SEEDS = __DIR__.'/db/seeds';

    const NAME = 'home';

    public function __construct(ContainerInterface $container)
    {
        $homePrefix = $container->get('home.prefix');
        $container->get(RendererInterface::class)->addPath('home', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get("$homePrefix", HomeAction::class, 'home.index');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/pages", PageCrudAction::class, "page.admin");
        }
    }
}
