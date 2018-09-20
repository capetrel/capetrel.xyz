<?php
namespace App\Auth\Action;

use App\Auth\DatabaseAuth;
use App\Auth\Event\LoginEvent;
use App\Event\EventManager;
use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class LoginAttemptAction
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
     * @var SessionInterface
     */
    private $session;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var EventManager
     */
    private $eventManager;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        DatabaseAuth $auth,
        Router $router,
        SessionInterface $session,
        EventManager $eventManager
    ) {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->router = $router;
        $this->session = $session;
        $this->eventManager = $eventManager;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $user = $this->auth->login($data['username'], $data['password']);
        if ($user) {
            $this->eventManager->trigger(new LoginEvent($user));
            $path = $this->session->get('auth.redirect') ?: $this->router->generateUri('admin');
            $this->session->delete('auth.redirect');
            (new FlashService($this->session))->success("Authentification réussi");
            return new RedirectResponse($path);
        } else {
            (new FlashService($this->session))->error('Identifiant ou mot de passe incorrect');
            return $this->redirect('auth.login');
        }
    }
}
