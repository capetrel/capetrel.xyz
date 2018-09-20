<?php
namespace App\Blog;

use App\Blog\Actions\CategoryCrudAction;
use App\Blog\Actions\CategoryShowAction;
use App\Blog\Actions\PostCrudAction;
use App\Blog\Actions\PostIndexAction;
use App\Blog\Actions\PostShowAction;
use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

/**
 * BlogModule gère les chemin et les vues.
 * Utilise le PostShowAction pour gérer les requêtes.
 */
class BlogModule extends Module
{

    const DEFINITIONS = __DIR__.DIRECTORY_SEPARATOR.'config.php';

    const MIGRATIONS = __DIR__.'/db/migrations/';

    const SEEDS = __DIR__.'/db/seeds';

    public const NAME = 'blog';

    public function __construct(ContainerInterface $container)
    {
        $blogPrefix = $container->get('blog.prefix');
        $container->get(RendererInterface::class)->addPath('blog', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get("$blogPrefix", PostIndexAction::class, 'blog.index');
        $router->get("$blogPrefix/{slug:[a-z\-0-9]+}-{id:[0-9]+}", PostShowAction::class, 'blog.show');
        $router->get("$blogPrefix/category/{slug:[a-z\-0-9]+}", CategoryShowAction::class, 'blog.category');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/posts", PostCrudAction::class, "blog.admin");
            $router->crud("$prefix/categories", CategoryCrudAction::class, "blog.category.admin");
        }
    }
}
