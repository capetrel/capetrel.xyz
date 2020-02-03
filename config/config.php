<?php
/**
 * Fichier de configuration générale de l'application
 * précise l'emplacement des vues et les classes à injecter en dépendences.
 */

use App\Framework\Twig\MinifyExtension;
use Framework\Router;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Renderer\TwigRendererFactory;
use App\Framework\Router\RouterFactory;
use App\Framework\Router\RouterTwigExtension;
use App\Framework\Session\PhpSession;
use App\Framework\Session\SessionInterface;
use App\Framework\SwiftMailerFactory;
use App\Framework\Twig\CsrfExtension;
use App\Framework\Twig\FlashExtension;
use App\Framework\Twig\FormExtension;
use App\Framework\Twig\ModuleExtension;
use App\Framework\Twig\PagerFantaExtension;
use App\Framework\Twig\PriceExtension;
use App\Framework\Twig\TextExtension;
use App\Framework\Twig\TimeExtension;
use Psr\Container\ContainerInterface;
use function \DI\get;
use function \DI\factory;
use function \DI\autowire;

return[
    'env' => env('ENV', 'production'),
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'capetrel',
    'views.path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views',
    'twig.extensions' => [
        get(RouterTwigExtension::class),
        get(PagerFantaExtension::class),
        get(TextExtension::class),
        get(TimeExtension::class),
        get(FlashExtension::class),
        get(FormExtension::class),
        get(CsrfExtension::class),
        get(ModuleExtension::class),
        get(PriceExtension::class),
        get(MinifyExtension::class),
    ],
    SessionInterface::class => autowire(PhpSession::class),
    CsrfMiddleware::class => autowire()->constructor(get(SessionInterface::class)),
    Router::class => factory(RouterFactory::class),
    RendererInterface::class => factory(TwigRendererFactory::class),
    PDO::class => function (ContainerInterface $c) {
        return new PDO(
            'mysql:host=' . $c->get('database.host') . ';dbname=' . $c->get('database.name'),
            $c->get('database.username'),
            $c->get('database.password'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    },
    'mail.to' => 'capetrel@mailtrap.io',
    'mail.from' => 'no-reply@admin.fr',
    Swift_Mailer::class => factory(SwiftMailerFactory::class)
];
