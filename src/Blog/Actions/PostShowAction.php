<?php
namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostShowAction
 * Gère le traitement des requêtes pour le module blog.
 * @package App\Blog\Actions
 */
class PostShowAction
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
     * @var PostTable
     */
    private $postTable;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        PostTable $postTable,
        Router $router
    ) {
        $this->renderer = $renderer;
        $this->postTable = $postTable;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postTable->findWithCategory($request->getAttribute('id'));
        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', [
                'slug'=> $post->slug,
                'id'=> $post->id,
            ]);
        }

        return $this->renderer->render('@blog/show', ['post' => $post]);
    }
}
