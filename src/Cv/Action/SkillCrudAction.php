<?php
namespace App\Cv\Action;

use App\Framework\Actions\CrudAction;
use App\Framework\Database\Table;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;

class SkillCrudAction extends CrudAction
{

    public function __construct(RendererInterface $renderer, Table $table, Router $router, FlashService $flash)
    {
        parent::__construct($renderer, $table, $router, $flash);
    }

}