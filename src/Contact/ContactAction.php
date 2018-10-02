<?php
namespace App\Contact;

use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use App\Framework\Table\PageTable;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

class ContactAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var string
     */
    private $to;

    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var PageTable
     */
    private $pageTable;


    public function __construct(
        string $to,
        RendererInterface $renderer,
        FlashService $flash,
        \Swift_Mailer $mailer,
        PageTable $pageTable
    ) {
        $this->renderer = $renderer;
        $this->to = $to;
        $this->flash = $flash;
        $this->mailer = $mailer;
        $this->pageTable = $pageTable;
    }

    /**
     * @param ServerRequestInterface $request
     * @return RedirectResponse|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $page = $this->pageTable->findPageContent('contact');
        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@contact/contact', compact('page'));
        }
        $data = $request->getParsedBody();
        $validator = (new Validator($data))
            ->required('name', 'email', 'message')
            ->textLength('name', 5)
            ->email('email')
            ->textLength('message', 10);

        if ($validator->isValid()) {
            $this->flash->success('Merci pour votre email');
            $message = new \Swift_Message('Formulaire de contact de votre site');
            $message->setTo($this->to);
            $message->setFrom($data['email']);
            $message->setBody($this->renderer->render('@contact/email/contact.text', $data));
            $message->addPart($this->renderer->render('@contact/email/contact.html', $data), 'text/html');
            $this->mailer->send($message);
            return new RedirectResponse((string)$request->getUri());
        } else {
            $this->flash->error('Merci de corriger les erreurs');
            $errors = $validator->getErrors();
            return $this->renderer->render('@contact/contact', compact('page', 'errors'));
        }
    }
}
