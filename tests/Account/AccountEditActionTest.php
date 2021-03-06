<?php
namespace Tests\Account;

use App\Account\Action\AccountEditAction;
use App\Account\User;
use App\Auth\Table\UserTable;
use App\Framework\AuthInterface;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\ActionTestCase;

class AccountEditActionTest extends ActionTestCase
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
    private $auth;

    /**
     * @var User
     */
    private $user;

    /**
     * @var ObjectProphecy
     */
    private $userTable;

    protected function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->user = new User();
        $this->user->id = 3;
        $this->auth = $this->prophesize(AuthInterface::class);
        $this->auth->getUser()->willReturn($this->user);
        $this->userTable = $this->prophesize(UserTable::class);
        $this->action = new AccountEditAction(
            $this->renderer->reveal(),
            $this->auth->reveal(),
            $this->prophesize(FlashService::class)->reveal(),
            $this->userTable->reveal()
        );
    }

    public function testValid()
    {
        $this->userTable->update(3, [
            'firstname' => 'Robert',
            'lastname' => 'Doe'
        ])->shouldBeCalled();
        $response = call_user_func($this->action, $this->makeRequest('/demo', [
            'firstname' => 'Robert',
            'lastname' => 'Doe'
        ]));
        $this->assertRedirect($response, '/demo');
    }

    public function testValidWithPassword()
    {
        $this->userTable->update(3, Argument::that(function ($data) {
            $this->assertEquals(['firstname', 'lastname', 'password'], array_keys($data));
            return true;
        }))->shouldBeCalled();
        $response = call_user_func($this->action, $this->makeRequest('/demo', [
            'firstname' => 'Robert',
            'lastname' => 'Doe',
            'password' => '0000',
            'password_confirm' => '0000'
        ]));
        $this->assertRedirect($response, '/demo');
    }

    public function testPostInvalid()
    {
        $this->userTable->update()->shouldNotBeCalled();
        $this->renderer->render('@account/account', Argument::that(function ($param) {
            $this->assertEquals(['password'], array_keys($param['errors']));
            return true;
        }));
        $response = call_user_func($this->action, $this->makeRequest('/demo', [
            'firstname' => 'Robert',
            'lastname' => 'Doe',
            'password' => '0000',
            'password_confirm' => '00'
        ]));
    }
}
