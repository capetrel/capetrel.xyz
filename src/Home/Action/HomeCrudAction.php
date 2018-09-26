<?php
namespace App\Home\Action;

use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use App\Home\Table\HomeTable;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class HomeCrudAction extends CrudAction
{

    protected $viewPath = "@home/admin";

    protected $routePrefix = "home.admin";

    public function __construct(
        RendererInterface $renderer,
        HomeTable $homeTable,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $homeTable, $router, $flash);
    }

    public function delete(ServerRequestInterface $request)
    {
        return parent::delete($request);
    }

    protected function prePersist(ServerRequestInterface $request, $home): array
    {
        $data = $request->getParsedBody();

        $data = array_filter($data, function ($key) {
            return in_array($key, ['proverb', 'proverb_author']);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        $validator = parent::getValidator($request)
            ->required('proverb', 'proverb_author')
            ->textLength('proverb', 10)
            ->textLength('proverb_author', 2, 250);

        return $validator;
    }

}
