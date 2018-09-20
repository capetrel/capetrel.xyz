<?php
namespace App\Auth\Action;

use App\Auth\Table\UserTable;
use App\Auth\User;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use Framework\Router;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

class PasswordResetAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var UserTable
     */
    private $userTable;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FlashService
     */
    private $flash;

    public function __construct(
        RendererInterface $renderer,
        UserTable $userTable,
        Router $router,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->userTable = $userTable;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        /** @var User $user */
        $user = $this->userTable->find($request->getAttribute('id'));
        if ($user->getPasswordResetAt() !== null &&
            $user->getPasswordReset() === $request->getAttribute('token') &&
            time() - $user->getPasswordResetAt()->getTimestamp() < 600
        ) {
            if ($request->getMethod() === 'GET') {
                return $this->renderer->render('@auth/reset');
            } else {
                $data = $request->getParsedBody();
                $validator = (new Validator($data))
                ->textLength('password', 4)
                ->confirm('password');
                if ($validator->isValid()) {
                    $this->userTable->updatePassword($user->getId(), $data['password']);
                    $this->flash->success('Votre mot de passe à bien été changé');
                    return new RedirectResponse($this->router->generateUri('auth.login'));
                } else {
                    $errors = $validator->getErrors();
                    return $this->renderer->render('@auth/reset', compact('errors'));
                }
            }
        } else {
            $this->flash->error('le token a expiré');
            return new RedirectResponse($this->router->generateUri('auth.password'));
        }
    }
}
