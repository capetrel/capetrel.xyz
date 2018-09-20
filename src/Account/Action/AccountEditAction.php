<?php
namespace App\Account\Action;

use App\Auth\Table\UserTable;
use App\Framework\AuthInterface;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

class AccountEditAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var AuthInterface
     */
    private $auth;

    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var UserTable
     */
    private $userTable;

    public function __construct(
        RendererInterface $renderer,
        AuthInterface $auth,
        FlashService $flash,
        UserTable $userTable
    ) {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->flash = $flash;
        $this->userTable = $userTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $user = $this->auth->getUser();
        $data = $request->getParsedBody();
        $validator = (new Validator($data))
            ->confirm('password')
            ->required('firstname', 'lastname');
        if ($validator->isValid()) {
            $userData = [
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname']
            ];
            if (!empty($data['password'])) {
                $userData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            $this->userTable->update($user->id, $userData);
            $this->flash->success('Votre compte à bien été mis à jour');
            return new RedirectResponse($request->getUri()->getPath());
        }
        $errors = $validator->getErrors();
        return $this->renderer->render('@account/account', compact('user', 'errors'));
    }
}
