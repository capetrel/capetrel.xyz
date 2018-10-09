<?php
namespace App\Portfolio\Action;

use App\Auth\Table\UserTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Database\Hydrator;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use App\Portfolio\Entity\Portfolio;
use App\Portfolio\PortfolioUpload;
use App\Portfolio\Table\TypeTable;
use App\Portfolio\Table\PortfolioTable;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class PortfolioCrudAction extends CrudAction
{
    protected $viewPath = "@portfolio/admin/portfolio";

    protected $routePrefix = "portfolio.admin";

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var PortfolioTable
     */
    protected $portfolioTable;

    /**
     * @var TypeTable
     */
    protected $typeTable;

    /**
     * @var UserTable
     */
    protected $userTable;

    /**
     * @var FlashService
     */
    protected $flash;

    /**
     * @var PortfolioUpload
     */
    private $porfolioUpload;

    protected $acceptedData = [
        'p_title', 'p_slug', 'p_description', 'image', 'link', 'type_id', 'user_id'
    ];

    public function __construct(
        RendererInterface $renderer,
        PortfolioTable $portfolioTable,
        Router $router,
        FlashService $flash,
        TypeTable $typeTable,
        UserTable $userTable,
        PortfolioUpload $porfolioUpload
    ) {
        parent::__construct($renderer, $portfolioTable, $router, $flash);
        $this->renderer = $renderer;
        $this->portfolioTable = $portfolioTable;
        $this->typeTable = $typeTable;
        $this->userTable = $userTable;
        $this->porfolioUpload = $porfolioUpload;
        $this->flash = $flash;
    }


    public function index(ServerRequestInterface $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->portfolioTable->findAllWithType()->paginate(12, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items', 'session'));
    }

    public function create(ServerRequestInterface $request)
    {
        $item = $this->getNewEntity();

        if ($request->getMethod() === 'POST') {
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($this->prePersist($request, $item));
                $this->postPersist($request, $item);
                $this->flash->success($this->flashMessages['create']);
                return $this->redirect($this->routePrefix . '.index');
            }
            Hydrator::hydrate($request->getParsedBody(), $item);
            $errors = $validator->getErrors();
        }

        return $this->renderer->render(
            $this->viewPath . '/create',
            $this->formParams(compact('item', 'errors'))
        );
    }

    public function edit(ServerRequestInterface $request)
    {
        $id = (int)$request->getAttribute('id');
        $item = $this->table->find($id);

        ['filename' => $filename, 'extension' => $extension] = pathinfo($item->getimage());
        $item->thumb = 'uploads' . DIRECTORY_SEPARATOR . 'portfolio' . DIRECTORY_SEPARATOR . $filename .'_thumb.' . $extension;

        if ($request->getMethod() === 'POST') {
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->update($id, $this->prePersist($request, $item));
                $this->postPersist($request, $item);
                $this->flash->success($this->flashMessages['edit']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $errors = $validator->getErrors();
            Hydrator::hydrate($request->getParsedBody(), $item);
        }

        return $this->renderer->render(
            $this->viewPath . '/edit',
            $this->formParams(compact('item', 'errors'))
        );
    }


    public function delete(ServerRequestInterface $request)
    {
        $portfolio = $this->table->find($request->getAttribute('id'));
        $this->porfolioUpload->deleteFile($portfolio->getImage());
        return parent::delete($request);
    }

    protected function formParams(array $params): array
    {
        $params['types'] = $this->typeTable->findList('t_name');
        $params['users'] = $this->userTable->findList('username');
        return $params;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Portfolio $portfolio
     * @return array
     */
    protected function prePersist(ServerRequestInterface $request, $portfolio): array
    {
        $data = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        $image = $this->porfolioUpload->upload($data['image'], $portfolio->getImage());
        if ($image) {
            $data['image'] = $image;
            $this->acceptedData[]= 'image';
        } else {
            unset($data['image']);
        }

        $data = array_filter($data, function ($key) {
            return in_array($key, $this->acceptedData);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        $validator = parent::getValidator($request)
            ->required('p_title', 'p_slug', 'p_description', 'image', 'type_id', 'user_id')
            ->textLength('p_description', 10)
            ->textLength('p_title', 2, 250)
            ->textLength('p_slug', 2, 50)
            ->exists('type_id', $this->typeTable->getTable(), $this->typeTable->getPdo())
            ->exists('user_id', $this->userTable->getTable(), $this->userTable->getPdo())
            ->extension('image', ['jpg', 'png'])
            ->isSlug('p_slug');
        // TODO ->fileSize('max_file_size', 2) en Mo

        if (is_null($request->getAttribute('id'))) {
            $validator
                ->uploaded('image');
        }
        return $validator;
    }
}
