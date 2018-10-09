<?php


use Phinx\Migration\AbstractMigration;

class TypeTable extends AbstractMigration
{
    public function change()
    {
        $this->table('types')
            ->addColumn('t_name', 'string')
            ->addColumn('t_slug', 'string')
            ->addIndex('t_slug', ['unique' => true])
            ->create();
    }
}
