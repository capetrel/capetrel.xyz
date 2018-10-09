<?php


use Phinx\Migration\AbstractMigration;

class CreateSkillsTable extends AbstractMigration
{
    public function change()
    {

        $this->table('types')
            ->addColumn('c_name', 'string')
            ->addColumn('c_level', 'string')
            ->addIndex('slug', ['unique' => true])
            ->create();

        $this->table('skills')
            ->addColumn('skill_name', 'string')
            ->addColumn('skill_level', 'integer')
            ->addColumn('skill_picto', 'string')
            ->addColumn('started_at', 'datetime')
            ->addColumn('ended_at', 'datetime')
            ->addColumn('description', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('place', 'string')
            ->addColumn('category_id', 'integer')
            ->addColumn('cv_id', 'integer')
            ->addForeignKey('category_id', 'types', 'id', [
                'delete' => 'SET NULL'
            ])
            ->addForeignKey('cv_id', 'cv', 'id', [
                'delete' => 'SET NULL'
            ])
            ->addIndex('category_id')
            ->addIndex('cv_id')
            ->create();
    }
}
