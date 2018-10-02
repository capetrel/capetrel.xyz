<?php
namespace App\Cv\Action;

use App\Cv\Entity\Cv;
use App\Cv\Entity\Skill;
use App\Cv\SkillUpload;
use App\CV\Table\CategoryTable;
use App\Cv\Table\CvTable;
use App\Cv\Table\SkillTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Session\FlashService;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class SkillCrudAction extends CrudAction
{
    protected $viewPath = "@cv/admin/skills";

    protected $routePrefix = "cv.admin.skills";

    protected $acceptedData = [
        'skill_name', 'skill_level', 'picto', 'started_at', 'ended_at', 'description', 'place', 'category_id', 'cv_id'
    ];

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var CategoryTable
     */
    protected $categoryTable;

    /**
     * @var CategoryTable
     */
    protected $cvTable;

    /**
     * @var SkillUpload
     */
    private $pictoUpload;

    public function __construct(
        RendererInterface $renderer,
        SkillTable $table,
        Router $router,
        FlashService $flash,
        CategoryTable $categoryTable,
        CvTable $cvTable,
        SkillUpload $pictoUpload
    ) {
        parent::__construct($renderer, $table, $router, $flash);
        $this->renderer = $renderer;
        $this->table = $table;
        $this->categoryTable = $categoryTable;
        $this->cvTable = $cvTable;
        $this->pictoUpload = $pictoUpload;
    }

    public function index(ServerRequestInterface $request): string
    {
        $slug = $request->getAttribute('slug');
        /** @var $cv Cv */
        $cv = $this->cvTable->findBy('c.cv_slug', $slug);
        $user_id = $cv->getUserId();
        $cv_id = $cv->getId();
        $author = $this->cvTable->findUserNames($user_id);

        $items = $this->table
            ->findAllSKillByCvId($cv_id)
            ->paginate(12, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items', 'cv', 'author', 'session'));
    }

    public function delete(ServerRequestInterface $request)
    {
        /** @var Skill $skill */
        $skill = $this->table->find($request->getAttribute('id'));
        $this->pictoUpload->deleteFile($skill->getPicto());
        return parent::delete($request);
    }

    protected function formParams(array $params): array
    {
        $params['categories'] = $this->categoryTable->findList('c_name');
        $params['cv'] = $this->cvTable->findList('cv_name');
        return $params;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Skill $skill
     * @return array
     */
    protected function prePersist(ServerRequestInterface $request, $skill): array
    {
        $params = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        $picto = $this->pictoUpload->upload($params['picto'], $skill->getPicto());
        if ($picto) {
            $params['picto'] = $picto;
        } else {
            unset($params['picto']);
        }

        return array_filter($params, function ($key) {
            return in_array($key, $this->acceptedData);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        $validator = parent::getValidator($request)
            ->required('skill_name', 'category_id', 'cv_id')
            ->textLength('skill_name', 2, 250)
            ->numericRange('skill_level', 0, 9)
            ->textLength('place', 0, 250)
            ->textLength('description', 0)
            ->emptyOrIsDate('started_at')
            ->emptyOrIsDate('ended_at')
            ->extension('picto', ['svg'])
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPdo())
            ->exists('cv_id', $this->cvTable->getTable(), $this->cvTable->getPdo());
        return $validator;
    }

}