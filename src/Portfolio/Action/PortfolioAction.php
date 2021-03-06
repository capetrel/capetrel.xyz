<?php
namespace App\Portfolio\Action;

use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use App\Portfolio\Table\PortfolioTable;
use App\Portfolio\Table\TypeTable;
use Psr\Http\Message\ServerRequestInterface;

class PortfolioAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var PortfolioTable
     */
    private $portfolioTable;
    /**
     * @var TypeTable
     */
    private $typeTable;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        PortfolioTable $portfolioTable,
        TypeTable $typeTable
    ) {
        $this->renderer = $renderer;
        $this->portfolioTable = $portfolioTable;
        $this->typeTable = $typeTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $images = $this->portfolioTable->findAll();
        $types = $this->typeTable->findAll();

        return $this->renderer->render('@portfolio/index', compact('images', 'types'));
    }
}
