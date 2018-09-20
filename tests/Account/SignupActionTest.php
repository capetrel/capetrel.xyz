<?php
namespace Tests\Account;

use App\Account\Action\SignupAction;
use App\Auth\DatabaseAuth;
use App\Auth\Table\UserTable;
use App\Auth\User;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\ActionTestCase;

class SignupActionTest extends ActionTestCase
{

    /**
     * @var ObjectProphecy de SignupAction
     */
    private $action;

    /**
     * @var ObjectProphecy de RendererInterface
     */
    private $renderer;

    /**
     * @var ObjectProphecy de UserTable
     */
    private $userTable;

    /**
     * @var ObjectProphecy de Router
     */
    private $router;

    /**
     * @var ObjectProphecy de DatabaseAuth
     */
    private $auth;

    /**
     * @var ObjectProphecy de FlashService
     */
    private $flash;

    public function setUp()
    {
        // UserTable
        $this->userTable = $this->prophesize(UserTable::class);
        $pdo = $this->prophesize(\PDO::class);
        $stmt = $this->getMockBuilder(\PDOStatement::class)->getMock();
        $stmt->expects($this->any())->method('fetchColumn')->willReturn(false);
        $pdo->prepare(Argument::any())->willReturn($stmt);
        $pdo->lastInsertId()->willReturn(3);
        $this->userTable->getTable()->willReturn('fake');
        $this->userTable->getPdo()->willReturn($pdo->reveal());
        // Renderer
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->renderer->render(Argument::any(), Argument::any())->willReturn('');
        // Router
        $this->router = $this->prophesize(Router::class);
        $this->router->generateUri(Argument::any())->will(function ($args) {
            return $args[0];
        });
        // FlashService
        $this->flash = $this->prophesize(FlashService::class);
        // Auth
        $this->auth = $this->prophesize(DatabaseAuth::class);
        $this->action = new SignupAction(
            $this->renderer->reveal(),
            $this->userTable->reveal(),
            $this->router->reveal(),
            $this->auth->reveal(),
            $this->flash->reveal()
        );
    }

    public function testGet()
    {
        call_user_func($this->action, $this->makeRequest());
        $this->renderer->render('@account/signup')->shouldHaveBeenCalled();
    }

    public function testPostInvalid()
    {
        call_user_func($this->action, $this->makeRequest('/demo', [
            'username' => 'Robert',
            'email' => 'Robert',
            'password' => '0000',
            'password_confirm' => '000',
        ]));
        $this->renderer->render('@account/signup', Argument::that(function ($params) {
            $this->assertArrayHasKey('errors', $params);
            $this->assertEquals(['email', 'password'], array_keys($params['errors']));
            return true;
        }))->shouldHaveBeenCalled();
    }

    public function testPostValid()
    {
        $this->userTable->insert(Argument::that(function (array $userParams) {
            $this->assertArraySubset([
                'username' => 'Robert',
                'email' => 'robert@doe.fr',
            ], $userParams);
            $this->assertTrue(password_verify('0000', $userParams['password']));
            return true;
        }))->shouldBeCalled();

        $this->auth->setUser(Argument::that(function (User $user) {
            $this->assertEquals('Robert', $user->username);
            $this->assertEquals('robert@doe.fr', $user->email);
            $this->assertEquals(3, $user->id);
            return true;
        }))->shouldBeCalled();

        $this->renderer->render()->shouldNotBeCalled();
        $this->flash->success(Argument::type('string'))->shouldBeCalled();
        $response = call_user_func($this->action, $this->makeRequest('/demo', [
            'username' => 'Robert',
            'email' => 'robert@doe.fr',
            'password' => '0000',
            'password_confirm' => '0000',
        ]));
        $this->assertRedirect($response, 'account');
    }

    public function testPostWithNopassword()
    {
        call_user_func($this->action, $this->makeRequest('/demo', [
            'username' => 'Robert',
            'email' => 'Robert',
            'password' => '',
            'password_confirm' => '',
        ]));
        $this->renderer->render('@account/signup', Argument::that(function ($params) {
            $this->assertArrayHasKey('errors', $params);
            $this->assertEquals(['email', 'password'], array_keys($params['errors']));
            return true;
        }))->shouldHaveBeenCalled();
    }
}
