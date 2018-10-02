<?php
namespace App\Portfolio\Action;

use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class PortfolioAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer
    ) {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $hello = 'Salut !';

        return $this->renderer->render('@portfolio/index', compact('hello'));
    }
}
