<?php
namespace App\Auth\Action;

use App\Auth\DatabaseAuth;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface;

class LogoutAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var DatabaseAuth
     */
    private $auth;
    /**
     * @var FlashService
     */
    private $flash;

    public function __construct(RendererInterface $renderer, DatabaseAuth $auth, FlashService $flash)
    {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->auth->logout();
        $this->flash->success("Vous êtes maintenant déconnecté");
        return new RedirectResponse('/login');
    }
}
