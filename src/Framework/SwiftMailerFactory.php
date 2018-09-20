<?php
namespace App\Framework;

use Psr\Container\ContainerInterface;

class SwiftMailerFactory
{
    public function __invoke(ContainerInterface $container):\Swift_Mailer
    {
        if ($container->get('env') === 'production') {
            $transport = new \Swift_SendmailTransport();
        } else {
            $transport = (new \Swift_SmtpTransport('smtp.mailtrap.io', 2525))
            ->setUsername('a0aa2864a4bf1b')
            ->setPassword('1b02a882cb1c74');
        }
        return new \Swift_Mailer($transport);
    }
}
