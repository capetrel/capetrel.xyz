<?php
namespace App\Account\Action;

use App\Auth\DatabaseAuth;
use App\Auth\Table\UserTable;
use App\Auth\User;
use App\Framework\Database\Hydrator;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use Framework\Router;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

class SignupAction
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
     * @var DatabaseAuth
     */
    private $auth;
    /**
     * @var FlashService
     */
    private $flash;

    public function __construct(
        RendererInterface $renderer,
        UserTable $userTable,
        Router $router,
        DatabaseAuth $auth,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->userTable = $userTable;
        $this->router = $router;
        $this->auth = $auth;
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@account/signup');
        }
        $data = $request->getParsedBody();
        $validator = (new Validator($data))
            ->required('username', 'email', 'password', 'password_confirm')
            ->textLength('username', 5)
            ->email('email')
            ->confirm('password')
            ->textLength('password', 4)
            ->isUnique('username', $this->userTable)
            ->isUnique('email', $this->userTable);
        if ($validator->isValid()) {
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ];
            $this->userTable->insert($userData);
            $user = Hydrator::hydrate($userData, User::class);
            $user->id = $this->userTable->getPdo()->lastInsertId();
            $this->auth->setUser($user);
            $this->flash->success('Votre compte à bien été créé');
            return new RedirectResponse($this->router->generateUri('account'));
        }
        $errors = $validator->getErrors();
        return $this->renderer->render('@account/signup', [
            'errors' => $errors,
            'user' => [
                'username' => $data['username'],
                'email' => $data['email'],
            ]
        ]);
    }
}
