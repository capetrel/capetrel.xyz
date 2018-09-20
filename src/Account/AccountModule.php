<?php
namespace App\Account;

use App\Account\Action\AccountAction;
use App\Account\Action\AccountEditAction;
use App\Account\Action\SignupAction;
use App\Framework\Auth\LoggedMiddleware;
use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use Framework\Router;

class AccountModule extends Module
{

    const MIGRATIONS = __DIR__ . '/migrations';

    const DEFINITIONS = __DIR__ . '/definitions.php';

    public const NAME = 'account';

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('account', __DIR__ .'/views');
        $router->get('/inscription', SignupAction::class, 'account.signup');
        $router->post('/inscription', SignupAction::class);
        $router->get('/mon-profil', [LoggedMiddleware::class, AccountAction::class], 'account');
        $router->post('/mon-profil', [LoggedMiddleware::class, AccountEditAction::class]);
    }
}
