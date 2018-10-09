<?php
namespace App\Portfolio;

use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use App\Portfolio\Action\TypeCrudAction;
use App\Portfolio\Action\TypeShowAction;
use Framework\Router;
use Psr\Container\ContainerInterface;
use App\Portfolio\Action\PortfolioAction;
use App\Portfolio\Action\PortfolioCrudAction;

class PortfolioModule extends Module
{

    const DEFINITIONS = __DIR__.'/config.php';

    const MIGRATIONS = __DIR__.'/db/migrations/';

    const NAME = 'portfolio';

    public function __construct(ContainerInterface $container)
    {
        $portfolioPrefix = $container->get('portfolio.prefix');
        $container->get(RendererInterface::class)->addPath('portfolio', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get("$portfolioPrefix", PortfolioAction::class, 'portfolio');
        $router->get("$portfolioPrefix/type/{slug:[a-z\-0-9]+}", TypeShowAction::class, 'portfolio.type');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/portfolio", PortfolioCrudAction::class, "portfolio.admin");
            $router->crud("$prefix/types", TypeCrudAction::class, "portfolio.types.admin");
        }
    }

}
