<?php
namespace App\Home\Action;

use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Table\PageTable;
use App\Home\Table\HomeTable;
use Psr\Http\Message\ServerRequestInterface;

class HomeAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var HomeTable
     */
    private $homeTable;

    /**
     * @var PageTable
     */
    private $pageTable;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        HomeTable $homeTable,
        PageTable $pageTable
    ) {
        $this->renderer = $renderer;
        $this->homeTable = $homeTable;
        $this->pageTable = $pageTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $page = $this->pageTable->findPageContent('home');
        $item = $this->homeTable->randomEntry();
        return $this->renderer->render('@home/index', compact('item', 'page'));
    }
}
