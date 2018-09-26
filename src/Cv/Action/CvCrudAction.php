<?php
namespace App\Cv\Action;

use App\Auth\Table\UserTable;
use App\Cv\Entity\Cv;
use App\Cv\Table\CvTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class CvCrudAction extends CrudAction
{

    protected $viewPath = "@cv/admin";

    protected $routePrefix = "cv.admin";

    /**
     * @var UserTable
     */
    protected $userTable;

    /**
     * @var CvTable
     */
    protected $cvTable;

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(
        RendererInterface $renderer,
        CvTable $cvTable,
        UserTable $userTable,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $cvTable, $router, $flash);
        $this->renderer = $renderer;
        $this->cvTable = $cvTable;
        $this->userTable = $userTable;
    }

    public function index(ServerRequestInterface $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->makeQuery()
            ->select('c.*, u.first_name, u.last_name')
            ->join('users as u', 'c.user_id = u.id')
            ->paginate(12, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items', 'session'));
    }

    public function delete(ServerRequestInterface $request)
    {
        return parent::delete($request);
    }

    protected function formParams(array $params): array
    {
        $params['users'] = $this->userTable->findList('username');
        return $params;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Cv $cv
     * @return array
     */
    protected function prePersist(ServerRequestInterface $request, $cv): array
    {
        $data = array_merge($request->getParsedBody());

        $data = array_filter($data, function ($key) {
            return in_array($key, ['cv_name', 'user_id']);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        $validator = parent::getValidator($request)
            ->required('cv_name', 'user_id')
            ->textLength('cv_name', 2, 250);

        return $validator;
    }

}
