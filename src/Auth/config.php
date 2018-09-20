<?php

use App\Auth\AuthTwigExtension;
use App\Auth\DatabaseAuth;
use App\Auth\ForbiddenMiddleware;
use App\Auth\Mailer\PasswordResetMailer;
use App\Auth\User;
use App\Framework\Auth\UserInterface;
use App\Framework\AuthInterface;
use function \DI\get;
use function \DI\add;
use function \DI\factory;
use function \DI\autowire;

return [
    'auth.login' => '/login',
    'auth.entity' => User::class,
    'twig.extensions' => add([get(AuthTwigExtension::class)]),
    UserInterface::class => factory(function (AuthInterface $auth) {
        return $auth->getUser();
    })->parameter('auth', get(AuthInterface::class)),
    AuthInterface::class => get(DatabaseAuth::class),
    User::class => autowire()->constructorParameter('entity', get('auth.entity')),
    ForbiddenMiddleware::class => autowire()->constructorParameter('loginPath', get('auth.login')),
    PasswordResetMailer::class => autowire()->constructorParameter('from', get('mail.from'))
];
