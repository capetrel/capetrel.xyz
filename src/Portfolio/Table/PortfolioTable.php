<?php
namespace App\Portfolio\Table;

use App\Auth\Table\UserTable;
use App\Framework\Database\QueryBuilder;
use App\Framework\Database\Table;
use App\Portfolio\Entity\Portfolio;

class PortfolioTable extends Table
{

    protected $entity = Portfolio::class;

    protected $table = 'portfolio';

    public function findAllWithType(): QueryBuilder
    {
        $type = new TypeTable($this->getPdo());
        $user = new UserTable($this->getPdo());
        return $this->makeQuery()
            ->select('p.*, t.t_name as type_name, u.username as username')
            ->join($type->getTable() . ' as t', 't.id = p.type_id')
            ->join($user->getTable() . ' as u', 'u.id = p.user_id')
            ->order('t.t_name ASC');
    }

    public function findForCategory(int $id): QueryBuilder
    {
        return $this->findAll()
            ->where("p.type_id = $id");
    }

}
