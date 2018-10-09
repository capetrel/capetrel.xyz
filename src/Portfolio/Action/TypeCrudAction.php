<?php
namespace App\Portfolio\Action;

use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use App\Portfolio\Table\TypeTable;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class TypeCrudAction extends CrudAction
{

    protected $viewPath = "@portfolio/admin/types";

    protected $routePrefix = "portfolio.types.admin";

    protected $acceptedData = ['t_name', 't_slug'];

    public function __construct(
        RendererInterface $renderer,
        TypeTable $categoryTable,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $categoryTable, $router, $flash);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('t_name', 't_slug')
            ->textLength('t_name', 2, 50)
            ->textLength('t_slug', 2, 50)
            ->isUnique('t_slug', $this->table->getTable(), $this->table->getPdo(), $request->getAttribute('id'))
            ->isSlug('t_slug');
    }
}
