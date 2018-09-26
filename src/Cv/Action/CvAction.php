<?php
namespace App\Cv\Action;

use App\CV\Table\CategoryTable;
use App\Cv\Table\CvTable;
use App\Framework\Actions\RouterAwareAction;
use App\Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class CvAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var CvTable
     */
    private $cvTable;

    /**
     * @var CategoryTable
     */
    private $categoryTable;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        CvTable $cvTable,
        CategoryTable $categoryTable
    ) {
        $this->renderer = $renderer;
        $this->cvTable = $cvTable;
        $this->categoryTable = $categoryTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $user = $this->cvTable->findUser(1);
        $cv = $this->cvTable->findCv(1);
        $studentSkills = $this->cvTable->findUserCvContentByCategory(1, 'diplomes-formations');
        $devSkills = $this->cvTable->findUserCvContentByCategory(1, 'programmation-logiciels');
        $proSkills = $this->cvTable->findUserCvContentByCategory(1, 'experiences-competences');
        $langSkills = $this->cvTable->findUserCvContentByCategory(1, 'langues');
        $surveykills = $this->cvTable->findUserCvContentByCategory(1, 'veilles');
        $socialNetSkills = $this->cvTable->findUserCvContentByCategory(1, 'reseau-sociaux');

        return $this->renderer->render('@cv/index', compact(
            'user',
            'cv',
            'studentSkills',
            'devSkills',
            'proSkills',
            'langSkills',
            'surveykills',
            'socialNetSkills'
        ));
    }
}