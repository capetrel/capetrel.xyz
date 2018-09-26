<?php
namespace App\Cv\Action;

use App\CV\Table\CategoryTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class SkillCrudAction extends CrudAction
{
    protected $viewPath = "@cv/admin/skills";

    protected $routePrefix = "cv.skills.admin";

    public function __construct(
        RendererInterface $renderer,
        CategoryTable $categoryTable,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $categoryTable, $router, $flash);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('name', 'slug');
    }

}