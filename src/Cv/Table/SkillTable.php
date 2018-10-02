<?php
namespace App\Cv\Table;

use App\Cv\Entity\Skill;
use App\Framework\Database\Table;

class SkillTable extends Table
{

    protected $entity = Skill::class;

    protected $table = 'skills';

    public function findAllSKillByCvId(int $id)
    {
        return $this->makeQuery()
            ->select('
                s.id,
                s.skill_name,
                s.skill_level,
                s.picto,
                s.started_at,
                s.ended_at,
                s.description,
                s.place,
                ca.c_name
            ')
            ->join('categories AS ca', 's.category_id = ca.id')
            ->where("s.cv_id = $id")
            ->order('ca.c_name ASC');
    }
}
