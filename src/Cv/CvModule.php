<?php
namespace App\Cv;

use App\Cv\Action\CategoryCrudAction;
use App\Cv\Action\CvAction;
use App\Cv\Action\CvCrudAction;
use App\Cv\Action\SkillCrudAction;
use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

class CvModule extends Module
{
    const DEFINITIONS = __DIR__.'/config.php';

    const MIGRATIONS = __DIR__.'/db/migrations/';

    const SEEDS = __DIR__.'/db/seeds';

    public const NAME = 'cv';

    public function __construct(ContainerInterface $container)
    {
        $cvPrefix = $container->get('cv.prefix');
        $container->get(RendererInterface::class)->addPath('cv', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get("$cvPrefix", CvAction::class, 'cv');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/cv", CvCrudAction::class, "cv.admin");
            $router->get("$prefix/cv/{slug:[a-z\-0-9]+}", SkillCrudAction::class, "cv.admin.show");
            $router->crud("$prefix/cv/{slug:[a-z\-0-9]+}/skill", SkillCrudAction::class, "cv.admin.skills");
            $router->crud("$prefix/categories", CategoryCrudAction::class, "cv.category.admin");
        }
    }

}
