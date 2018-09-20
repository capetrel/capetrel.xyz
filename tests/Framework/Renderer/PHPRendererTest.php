<?php
namespace Tests\Framework;

use App\Framework\Renderer\PhpRenderer;
use PHPUnit\Framework\TestCase;

class PhpRendererTest extends TestCase
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new PhpRenderer(__DIR__ . '/views');
    }

    public function testRenderTheRightPath()
    {
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $content = $this->renderer->render('@blog/demo');
        $this->assertEquals('Salut les gens !', $content);
    }

    public function testRenderTheDefaultPath()
    {
        $content = $this->renderer->render('demo');
        $this->assertEquals('Salut les gens !', $content);
    }

    public function testRenderWithParams()
    {
        $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
        $this->assertEquals('Salut Marc', $content);
    }

    public function testGlobalParams()
    {
        $this->renderer->addGlobals('nom', 'Marc');
        $content = $this->renderer->render('demoparams');
        $this->assertEquals('Salut Marc', $content);
    }
}
