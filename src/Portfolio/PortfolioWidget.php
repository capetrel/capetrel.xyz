<?php
namespace App\Portfolio;

use App\Admin\AdminWidgetInterface;
use App\Framework\Renderer\RendererInterface;
use App\Portfolio\Table\PortfolioTable;

class PortfolioWidget implements AdminWidgetInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var PortfolioTable
     */
    private $porfolioTable;

    public function __construct(RendererInterface $renderer, PortfolioTable $porfolioTable)
    {
        $this->renderer = $renderer;
        $this->porfolioTable = $porfolioTable;
    }

    public function render(): string
    {
        $count = $this->porfolioTable->count();
        return $this->renderer->render('@portfolio/admin/widget', compact('count'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@portfolio/admin/menu');
    }
}