<?php
namespace App\Game;

use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use App\Game\Action\GameAction;
use Framework\Router;
use Psr\Container\ContainerInterface;

class GameModule extends Module
{
    const DEFINITIONS = __DIR__.'/config.php';

    const NAME = 'game';

    public function __construct(ContainerInterface $container)
    {
        $gamePrefix = $container->get('game.prefix');
        $container->get(RendererInterface::class)->addPath('game', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get("$gamePrefix", GameAction::class, 'game');
    }
}
