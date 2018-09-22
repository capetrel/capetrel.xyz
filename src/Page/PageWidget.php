<?php
namespace App\Page;

use App\Admin\AdminWidgetInterface;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Table\PageTable;

class PageWidget implements AdminWidgetInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var PageTable
     */
    private $pageTable;

    public function __construct(RendererInterface $renderer, PageTable $pageTable)
    {
        $this->renderer = $renderer;
        $this->pageTable = $pageTable;
    }

    public function render(): string
    {
        $count = $this->pageTable->count();
        return $this->renderer->render('@page/admin/widget', compact('count'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@page/admin/menu');
    }
}