<?php

use Phinx\Migration\AbstractMigration;

class CreatePageTable extends AbstractMigration
{

    public function change()
    {
        $this->table('pages')
            ->addColumn('module_name', 'string')
            ->addColumn('title', 'string')
            ->addColumn('menu_title', 'string')
            ->addColumn('content', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->create();
    }
}
