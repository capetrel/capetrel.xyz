<?php


use Phinx\Migration\AbstractMigration;

class CreateCvTable extends AbstractMigration
{
    public function change()
    {
        $this->table('cv')
            ->addColumn('cv_name', 'string')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'SET NULL'
            ])
            ->addIndex('user_id')
            ->create();

    }

}
