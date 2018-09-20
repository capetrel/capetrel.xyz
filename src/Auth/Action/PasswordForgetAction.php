<?php
namespace App\Auth\Action;

use App\Auth\Mailer\PasswordResetMailer;
use App\Auth\Table\UserTable;
use App\Framework\Database\NoRecordException;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

class PasswordForgetAction
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
     * @var PasswordResetMailer
     */
    private $mailer;

    /**
     * @var FlashService
     */
    private $flash;

    public function __construct(
        RendererInterface $renderer,
        UserTable $userTable,
        PasswordResetMailer $mailer,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->userTable = $userTable;
        $this->mailer = $mailer;
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@auth/password');
        }
        $params = $request->getParsedBody();
        $validator = (new Validator($params))
            ->notEmpty('email')
            ->email('email');
        if ($validator->isValid()) {
            try {
                $user = $this->userTable->findBy('email', $params['email']);
                $token = $this->userTable->resetPassword($user->id);
                $this->mailer->send($user->email, [
                    'id' => $user->id,
                    'token' => $token,
                ]);
                $this->flash->success('Un email vous a été envoyé');
                return new RedirectResponse($request->getUri()->getPath());
            } catch (NoRecordException $e) {
                $errors = ['email' => "Aucun utilisateur ne correspond à cet email"];
            }
        } else {
            $errors = $validator->getErrors();
        }
        return $this->renderer->render('@auth/password', compact('errors'));
    }
}
