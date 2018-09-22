<?php
namespace App\Home;

use App\Admin\AdminWidgetInterface;
use App\Framework\Renderer\RendererInterface;
use App\Home\Table\HomeTable;

class HomeWidget implements AdminWidgetInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var HomeTable
     */
    private $homeTable;

    public function __construct(RendererInterface $renderer, HomeTable $homeTable)
    {
        $this->renderer = $renderer;
        $this->homeTable = $homeTable;
    }

    public function render(): string
    {
        $count = $this->homeTable->count();
        return $this->renderer->render('@home/admin/widget', compact('count'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@home/admin/menu');
    }
}
