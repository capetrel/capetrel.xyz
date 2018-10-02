<?php
namespace App\Cv\Action;

use App\CV\Table\CategoryTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class CategoryCrudAction extends CrudAction
{

    protected $viewPath = "@cv/admin/categories";

    protected $routePrefix = "cv.category.admin";

    protected $acceptedData = ['c_name', 'c_slug'];

    public function __construct(
        RendererInterface $renderer,
        CategoryTable $table,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $table, $router, $flash);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('c_name', 'c_slug')
            ->textLength('c_name', 2, 250)
            ->textLength('c_slug', 2, 50)
            ->isUnique('c_slug', $this->table->getTable(), $this->table->getPdo(), $request->getAttribute('id'))
            ->isSlug('c_slug');
    }
}
