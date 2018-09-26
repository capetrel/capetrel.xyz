<?php
namespace App\Cv;

use App\Admin\AdminWidgetInterface;
use App\Cv\Table\CvTable;
use App\Framework\Renderer\RendererInterface;

class CvWidget implements AdminWidgetInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var CvTable
     */
    private $cvTable;

    public function __construct(RendererInterface $renderer, CvTable $cvTable)
    {
        $this->renderer = $renderer;
        $this->cvTable = $cvTable;
    }

    public function render(): string
    {
        $cv = $this->cvTable->count();
        return $this->renderer->render('@cv/admin/widget', compact('cv'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@cv/admin/menu');
    }
}