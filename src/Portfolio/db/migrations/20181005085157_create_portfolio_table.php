<?php


use Phinx\Migration\AbstractMigration;

class CreatePortfolioTable extends AbstractMigration
{

    public function change()
    {
        $this->table('portfolio')
            ->addColumn('p_title', 'string')
            ->addColumn('p_slug', 'string')
            ->addColumn('p_description', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('thumb', 'string')
            ->addColumn('image', 'string')
            ->addColumn('category_id', 'integer')
            ->addForeignKey('category_id', 'types', 'id', [
                'delete' => 'NO ACTION'
            ])
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'NO ACTION'
            ])
            ->create();
    }
}
