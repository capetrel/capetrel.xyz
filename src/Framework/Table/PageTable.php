<?php
namespace App\Framework\Table;

use App\Framework\Database\Table;
use App\Framework\Entity\Page;

class PageTable extends Table
{
    protected $entity = Page::class;

    protected $table = 'pages';

    public function findPageContent(string $moduleName)
    {
        // TODO catch exception et redirection 404 si pas de module
        return $this->findBy('module_name', $moduleName);
    }
}
