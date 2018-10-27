<?php
namespace App\Account\Action;

use App\Account\AccountUpload;
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

    /**
     * @var AccountUpload
     */
    private $accountUpload;

    public function __construct(
        RendererInterface $renderer,
        AuthInterface $auth,
        FlashService $flash,
        UserTable $userTable,
        AccountUpload $accountUpload
    ) {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->flash = $flash;
        $this->userTable = $userTable;
        $this->accountUpload = $accountUpload;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $user = $this->auth->getUser();
        $data = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        $validator = (new Validator($data))
            ->confirm('password')
            ->required('first_name', 'last_name')
            ->extension('image', ['jpg', 'png']);
        $avatar = $this->accountUpload->upload($data['avatar'], $user->getAvatar());
        if ($avatar) {
            $data['avatar'] = $avatar;
        } else {
            ($data['avatar'] = $user->getAvatar());
        }

        if ($validator->isValid()) {
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'birthday' => $data['birthday'],
                'tel_1' => $data['tel_1'],
                'tel_2' => $data['tel_2'],
                'driver_licence' => $data['driver_licence'],
                'address' => $data['address'],
                'description' => $data['description'],
                'avatar' => $data['avatar']
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
