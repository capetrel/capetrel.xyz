<?php


use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{

    public function change()
    {
        $this->table('users')
            ->addColumn('username', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('birthday', 'datetime')
            ->addColumn('tel_1', 'string')
            ->addColumn('tel_2', 'string')
            ->addColumn('driver_licence', 'string')
            ->addColumn('address', 'string')
            ->addColumn('description', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addIndex(['email', 'username'], ['unique' => true])
            ->create();
    }
}
