<?php
namespace Tests\Auth\Action;

use App\Auth\Action\PasswordForgetAction;
use App\Auth\Mailer\PasswordResetMailer;
use App\Auth\Table\UserTable;
use App\Auth\User;
use App\Framework\Database\NoRecordException;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\ActionTestCase;

class PaswwordForgetTest extends ActionTestCase
{

    /**
     * @var ObjectProphecy
     */
    private $renderer;

    /**
     * @var ObjectProphecy
     */
    private $action;

    /**
     * @var ObjectProphecy
     */
    private $userTable;

    /**
     * @var ObjectProphecy
     */
    private $mailer;

    /**
     * @var ObjectProphecy
     */
    private $flash;

    protected function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->userTable = $this->prophesize(UserTable::class);
        $this->mailer = $this->prophesize(PasswordResetMailer::class);
        $this->flash = $this->prophesize(FlashService::class);
        $this->action = new PasswordForgetAction(
            $this->renderer->reveal(),
            $this->userTable->reveal(),
            $this->mailer->reveal(),
            $this->flash->reveal()
        );
    }

    public function testEmailInvalid()
    {
        $request = $this->makeRequest('/demo', ['email' => 'azeazeaz']);
        $this->renderer
            ->render(Argument::type('string'), Argument::withEntry('errors', Argument::withKey('email')))
            ->shouldBeCalled()
            ->willReturnArgument();
        $response = call_user_func($this->action, $request);
        $this->assertEquals('@auth/password', $response);
    }

    public function testEmailNotExist()
    {
        $request = $this->makeRequest('/demo', ['email' => 'john@doe.fr']);
        $this->userTable->findBy('email', 'john@doe.fr')->willThrow(new NoRecordException());
        $this->renderer
            ->render(Argument::type('string'), Argument::withEntry('errors', Argument::withKey('email')))
            ->shouldBeCalled()
            ->willReturnArgument();
        $response = call_user_func($this->action, $request);
        $this->assertEquals('@auth/password', $response);
    }

    public function testWithGoodEmail()
    {
        $user = new User();
        $user->id = 3;
        $user->email = 'john@doe.fr';
        $token = 'fake';
        $request = $this->makeRequest('/demo', ['email' => $user->email]);
        $this->userTable->findBy('email', $user->email)->willReturn($user);
        $this->userTable->resetPassword(3)->willReturn($token);
        $this->mailer->send($user->email, [
            'id' => $user->id,
            'token' => $token
        ])->shouldBeCalled();
        $this->renderer->render()->shouldNotBeCalled();
        $response = call_user_func($this->action, $request);
        $this->assertRedirect($response, '/demo');
    }
}
