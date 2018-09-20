<?php
namespace App\Contact;

use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use Framework\Router;

class ContactModule extends Module
{

    const DEFINITIONS = __DIR__.'/config.php';

    public const NAME = 'contact';

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('contact', __DIR__ .'/views');
        $router->get('/contact', ContactAction::class, 'contact');
        $router->post('/contact', ContactAction::class);
    }
}
