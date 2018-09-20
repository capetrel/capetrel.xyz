<?php
namespace Tests\Contact;

use App\Contact\ContactAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Response\RedirectResponse;
use App\Framework\Session\FlashService;
use Tests\ActionTestCase;

class ContactActionTest extends ActionTestCase
{

    /**
     * @var ContactAction
     */
    private $action;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $to = "demo@local.dev";

    protected function setUp()
    {
        $this->renderer = $this->getMockBuilder(RendererInterface::class)->getMock();
        $this->flash = $this->getMockBuilder(FlashService::class)->disableOriginalConstructor()->getMock();
        $this->mailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $this->action =  new  ContactAction($this->to, $this->renderer, $this->flash, $this->mailer);
    }

    public function testGet()
    {
        $this->renderer->expects($this->once())
            ->method('render')
            ->with('@contact/contact')
            ->willReturn('');
        call_user_func($this->action, $this->makeRequest('/contact'));
    }

    public function testPostInvalid()
    {
        $request = $this->makeRequest('/contact', [
            'name' => 'Robert',
            'email' => 'azeazez',
            'message' => 'azearvca fu adoufo aea fadadou ado ae azearvca fu adoufo aea fadadou ado ae',
        ]);

        $this->renderer
            ->expects($this->once())
            ->method('render')
            ->with(
                '@contact/contact',
                $this->callback(function ($params) {
                    $this->assertArrayHasKey('errors', $params);
                    $this->assertArrayHasKey('email', $params['errors']);
                    return true;
                })
            )
            ->willReturn('');
        $this->flash->expects($this->once())->method('error');
        call_user_func($this->action, $request);
    }

    public function testPostValid()
    {
        $request = $this->makeRequest('/contact', [
            'name' => 'Robert',
            'email' => 'demo@local.dev',
            'message' => 'azearvca fu adoufo aea fadadou ado ae azearvca fu adoufo aea fadadou ado ae',
        ]);

        $this->flash->expects($this->once())->method('success');
        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (\Swift_Message $message) {
                $this->assertArrayHasKey($this->to, $message->getTo());
                $this->assertArrayHasKey('demo@local.dev', $message->getFrom());
                $this->assertContains('texttexttext', $message->toString());
                $this->assertContains('htmlhtmlhtml', $message->toString());
                return true;
            }));
        $this->renderer->expects($this->any())
            ->method('render')
            ->willReturn('texttexttext', 'htmlhtmlhtml');
        $response = call_user_func($this->action, $request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
