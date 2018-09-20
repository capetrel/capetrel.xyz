<?php
namespace App\Framework\Actions;

use App\Framework\Database\Hydrator;
use App\Framework\Database\Table;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Validator;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class CrudAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var FlashService
     */
    private $flash;

    /**
     * Chemin vers les vues
     * @var string
     */
    protected $viewPath;

    /**
     * @var string
     */
    protected $routePrefix;

    /**
     * @var array
     */
    protected $flashMessages = [
        'create' => 'L\'élément à bien été créé',
        'edit' => 'L\'élément à bien été modifié',
        'delete' => 'L\'élément à bien été supprimé'
    ];

    /**
     * @var array
     */
    protected $acceptedData = [];

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        Table $table,
        Router $router,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->table = $table;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->renderer->addGlobals('viewPath', $this->viewPath);
        $this->renderer->addGlobals('routePrefix', $this->routePrefix);

        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }

        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }

        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    /**
     * Affiche la liste des éléments
     * @param ServerRequestInterface $request
     * @return string
     */
    public function index(ServerRequestInterface $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findAll()->paginate(12, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items', 'session'));
    }

    /**
     * Edite un élément
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface|string
     * @throws \App\Framework\Database\NoRecordException
     */
    public function edit(ServerRequestInterface $request)
    {
        $id = (int)$request->getAttribute('id');
        $item = $this->table->find($id);

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

    /**
     * Crée un nouvel élément
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface|string
     */
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

    public function delete(ServerRequestInterface $request)
    {
        $this->table->delete($request->getAttribute('id'));
        return $this->redirect($this->routePrefix . '.index');
    }

    /**
     * Récupère les données de la requête HTTP
     * @param ServerRequestInterface $request
     * @return array
     */
    protected function prePersist(ServerRequestInterface $request, $item): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, $this->acceptedData);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Permet d'effectuer un traitement après la persistence
     * @param ServerRequestInterface $request
     * @param $item
     */
    protected function postPersist(ServerRequestInterface $request, $item): void
    {
    }

    /**
     * Génère le validateur pour valider les données
     * @param ServerRequestInterface $request
     * @return Validator
     */
    protected function getValidator(ServerRequestInterface $request)
    {
        return new Validator(array_merge($request->getParsedBody(), $request->getUploadedFiles()));
    }

    /**
     * Génère une nouvel entité pour l'action de création
     * @return mixed
     */
    protected function getNewEntity()
    {
        $entity = $this->table->getEntity();
        return new $entity;
    }

    /**
     * Permet de traiter les paramètre envoyés à la vue
     * @param array $params
     * @return array
     */
    protected function formParams(array $params): array
    {
        return $params;
    }
}
