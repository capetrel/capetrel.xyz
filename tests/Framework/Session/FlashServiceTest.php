<?php
namespace Tests\Framework\Session;

use App\Framework\Session\ArraySession;
use App\Framework\Session\FlashService;
use PHPUnit\Framework\TestCase;

class FlashServiceTest extends TestCase
{
    /**
     * @var FlashService
     */
    private $flashService;

    /**
     * @var ArraySession
     */
    private $session;

    public function setUp()
    {
        $this->session = new ArraySession();
        $this->flashService = new FlashService($this->session);
    }

    public function testDeleteFlashAfterGettingIt()
    {
        $this->flashService->success('Bravo');
        $this->assertEquals('Bravo', $this->flashService->get('success'));
        $this->assertNull($this->session->get('flash'));
        $this->assertEquals('Bravo', $this->flashService->get('success'));
        $this->assertEquals('Bravo', $this->flashService->get('success'));
    }
}
