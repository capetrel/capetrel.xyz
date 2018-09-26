<?php
namespace App\Cv\Table;

use App\Cv\Entity\Cv;
use App\Framework\Database\QueryResult;
use App\Framework\Database\Table;

class CvTable extends Table
{
    protected $entity = Cv::class;

    protected $table = 'cv';

    public function findUser(int $userId)
    {
        return $this->makeQuery()
            ->select('
                first_name,
                last_name,
                address,
                email,
                tel_1,
                driver_licence
            ')
            ->from('users')
            ->where("user_id = $userId")
            ->fetch();
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function findCv(int $userId)
    {
        return $this->makeQuery()
            ->select('c.cv_name')
            ->where("user_id = $userId")
            ->fetch();
    }

    /**
     * @param int $userId
     * @param string $categorySlug
     * @return QueryResult
     */
    public function findUserCvContentByCategory(int $userId, string $categorySlug)//: QueryResult
    {
        $category = new CategoryTable($this->getPdo());
        $skill = new SkillTable($this->getPdo());

        return $this->makeQuery()
            ->select('
                s.skill_name,
                s.skill_level,
                s.picto,
                s.started_at,
                s.ended_at,
                s.description,
                s.place,
                ca.c_name
            ')
            ->from('cv')
            ->join($skill->getTable() . ' as s', 'cv.id = s.cv_id')
            ->join($category->getTable() . ' as ca', 's.category_id = ca.id')
            ->where("c.user_id = $userId AND ca.c_slug = '$categorySlug'")
            ->fetchAll();
    }

    public function findCvAndUser()
    {
        return $this->makeQuery()
            ->select('u.first_name, u.last_name')
            ->join('users as u', 'c.user_id = u.id')
            ->fetchAll();
    }

}
