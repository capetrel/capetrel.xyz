<?php
namespace Tests\Framework\Auth;

use App\Auth\User;
use App\Framework\Auth\ForbiddenException;
use App\Framework\Auth\RoleMiddleware;
use App\Framework\AuthInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Server\RequestHandlerInterface;

class RoleMiddleWareTest extends TestCase
{

    /**
     * @var ObjectProphecy
     */
    private $middleware;

    /**
     * @var ObjectProphecy
     */
    private $auth;

    protected function setUp()
    {
        $this->auth = $this->prophesize(AuthInterface::class);
        $this->middleware = new RoleMiddleware(
            $this->auth->reveal(),
            'admin'
        );
    }

    public function testWithUnauthenticatedUser()
    {
        $this->auth->getUser()->willReturn(null);
        $this->expectException(ForbiddenException::class);
        $this->middleware->process(new ServerRequest('GET', '/demo'), $this->makeDelegate()->reveal());
    }

    public function testWithBadRole()
    {
        $user = $this->prophesize(User::class);
        $user->getRoles()->willReturn(['user']);
        $this->auth->getUser()->willReturn($user->reveal());
        $this->expectException(ForbiddenException::class);
        $this->middleware->process(new ServerRequest('GET', '/demo'), $this->makeDelegate()->reveal());
    }

    public function testWithGoodRole()
    {
        $user = $this->prophesize(User::class);
        $user->getRoles()->willReturn(['admin']);
        $this->auth->getUser()->willReturn($user->reveal());
        $delegate = $this->makeDelegate();
        $delegate
            ->handle(Argument::any())
            ->shouldBeCalled()
            ->willReturn(new Response());
        $this->middleware->process(new ServerRequest('GET', '/demo'), $delegate->reveal());
    }

    private function makeDelegate(): ObjectProphecy
    {
        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::any())->willReturn(new Response());
        return $delegate;
    }
}
