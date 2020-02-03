<?php
namespace App\Blog\Actions;

use App\Blog\Table\CategoryTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class CategoryCrudAction extends CrudAction
{

    protected $viewPath = "@blog/admin/categories";

    protected $routePrefix = "blog.category.admin";

    protected $acceptedData = ['name', 'slug'];

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
            ->required('name', 'slug')
            ->textLength('name', 2, 250)
            ->textLength('slug', 2, 50)
            ->unique('slug', $this->table->getTable(), $this->table->getPdo(), $request->getAttribute('id'))
            ->isSlug('slug');
    }
}
