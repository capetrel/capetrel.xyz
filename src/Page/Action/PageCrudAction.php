<?php
namespace App\Page\Action;

use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use App\Framework\Table\PageTable;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class PageCrudAction extends CrudAction
{
    protected $viewPath = "@page/admin";

    protected $routePrefix = "page.admin";

    public function __construct(
        RendererInterface $renderer,
        PageTable $table,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $table, $router, $flash);
    }

    public function delete(ServerRequestInterface $request)
    {
        return parent::delete($request);
    }

    protected function prePersist(ServerRequestInterface $request, $page): array
    {
        $data = $request->getParsedBody();

        $data = array_filter($data, function ($key) {
            return in_array($key, ['module_name', 'title', 'menu_title', 'content']);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        $validator = parent::getValidator($request)
            ->required('module_name', 'title', 'menu_title', 'content')
            ->textLength('content', 10)
            ->textLength('module_name', 2, 250)
            ->textLength('title', 2, 250)
            ->textLength('menu_title', 2, 250);

        return $validator;
    }
}