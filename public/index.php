<?php

use App\Home\Action\HomeAction;
use Framework\App;
use App\Admin\AdminModule;
use App\Auth\AuthModule;
use App\Account\AccountModule;
use App\Page\PageModule;
use App\Home\HomeModule;
use App\Cv\CvModule;
use App\Portfolio\PortfolioModule;
use App\Contact\ContactModule;
use App\Framework\Middleware\TrailingSlashMiddleware;
use App\Auth\ForbiddenMiddleware;
use App\Framework\Auth\RoleMiddlewareFactory;
use App\Framework\Middleware\MethodMiddleware;
use App\Framework\Middleware\RendererRequestMiddleware;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Middleware\RouterMiddleware;
use App\Framework\Middleware\RouteDispatcherMiddleware;
use App\Framework\Middleware\NotFoundMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Middlewares\Whoops;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$app = (new App(['config/config.php', 'config.php']))
    ->addModule(AdminModule::class)
    ->addModule(AuthModule::class)
    ->addModule(AccountModule::class)
    ->addModule(PageModule::class)
    ->addModule(HomeModule::class)
    ->addModule(CvModule::class)
    ->addModule(PortfolioModule::class)
    ->addModule(ContactModule::class);

$container = $app->getContainer();

// Permet de choisir quel module est la home page
$container->get(Framework\Router::class)->get('/', HomeAction::class, 'home');

$app->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(ForbiddenMiddleware::class)
    ->pipe(
        $container->get('admin.prefix'),
        $container->get(RoleMiddlewareFactory::class)->makeForRole('admin')
    )
    ->pipe(MethodMiddleware::class)
    ->pipe(RendererRequestMiddleware::class)
    //->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(RouteDispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);

if (php_sapi_name() !== "cli") {
    $response = $app->run(ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
