<?php
namespace App\Blog\Actions;

use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use App\Framework\Actions\RouterAwareAction;
use App\Framework\Auth\UserInterface;
use App\Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostShowAction
 * Gère le traitement des requêtes pour le module blog.
 * @package App\Blog\Actions
 */
class CategoryShowAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var PostTable
     */
    private $postTable;

    /**
     * @var CategoryTable
     */
    private $categoryTable;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        PostTable $postTable,
        CategoryTable $categoryTable
    ) {
        $this->renderer = $renderer;
        $this->postTable = $postTable;
        $this->categoryTable = $categoryTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $category = $this->categoryTable->findBy('slug', $request->getAttribute('slug'));
        $posts = $this->postTable->findPublicForCategory($category->id)->paginate(4, $params['p'] ?? 1);
        $categories = $this->categoryTable->findAll();
        $page = $params['p'] ?? 1;

        return $this->renderer->render('@blog/index', compact('posts', 'categories', 'category', 'page'));
    }
}
