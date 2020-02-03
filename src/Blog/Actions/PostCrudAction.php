<?php
namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\PostUpload;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class PostCrudAction extends CrudAction
{

    protected $viewPath = "@blog/admin/posts";

    protected $routePrefix = "blog.admin";

    /**
     * @var CategoryTable
     */
    protected $categoryTable;

    /**
     * @var PostUpload
     */
    private $postUpload;

    public function __construct(
        RendererInterface $renderer,
        PostTable $postTable,
        Router $router,
        FlashService $flash,
        CategoryTable $categoryTable,
        PostUpload $postUpload
    ) {
        parent::__construct($renderer, $postTable, $router, $flash);
        $this->categoryTable = $categoryTable;
        $this->postUpload = $postUpload;
    }

    public function delete(ServerRequestInterface $request)
    {
        $post = $this->table->find($request->getAttribute('id'));
        $this->postUpload->deleteFile($post->image);
        return parent::delete($request);
    }

    protected function formParams(array $params): array
    {
        $params['categories'] = $this->categoryTable->findList();
        $params['categories']['123123'] = 'Categorie fake';
        return $params;
    }

    protected function getNewEntity()
    {
        $post = new Post();
        $post->created_at = new \DateTime();
        return $post;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Post $post
     * @return array
     */
    protected function prePersist(ServerRequestInterface $request, $post): array
    {
        $data = array_merge($request->getParsedBody(), $request->getUploadedFiles());

        $image = $this->postUpload->upload($data['image'], $post->image);
        if ($image) {
            $data['image'] = $image;
        } else {
            unset($data['image']);
        }

        $data = array_filter($data, function ($key) {
            return in_array($key, ['name', 'slug', 'content', 'created_at', 'category_id', 'image', 'published']);
        }, ARRAY_FILTER_USE_KEY);
        return array_merge($data, ['updated_at' => date('Y-m-d H:i:s')]);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        $validator = parent::getValidator($request)
            ->required('content', 'name', 'slug', 'created_at', 'category_id')
            ->textLength('content', 10)
            ->textLength('name', 2, 250)
            ->textLength('slug', 2, 50)
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPdo())
            ->isDateTime('created_at')
            ->extension('image', ['jpg', 'png'])
            ->isSlug('slug');
            // TODO ->fileSize('max_file_size', 2) en Mo

        if (is_null($request->getAttribute('id'))) {
            $validator
                ->uploaded('image');
        }
        return $validator;
    }
}
