<?php


use Phinx\Migration\AbstractMigration;

class CreateHomeTable extends AbstractMigration
{

    public function change()
    {
        $this->table('proverbs')
            ->addColumn('proverb', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('proverb_author', 'string')
            ->create();
    }

}

