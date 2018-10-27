<?php
namespace App\Game\Action;

use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Table\PageTable;
use Psr\Http\Message\ServerRequestInterface;

class GameAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var PageTable
     */
    private $pageTable;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        PageTable $pageTable
    ) {
        $this->renderer = $renderer;
        $this->pageTable = $pageTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $page = $this->pageTable->findPageContent('game');
        return $this->renderer->render('@game/index', compact('page'));
    }
}