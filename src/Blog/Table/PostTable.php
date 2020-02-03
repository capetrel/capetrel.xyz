<?php
namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\QueryBuilder;
use App\Framework\Database\Table;

class PostTable extends Table
{

    protected $entity = Post::class;

    protected $table = 'posts';

    public function findAll(): QueryBuilder
    {
        $category = new CategoryTable($this->getPdo());
        return $this->makeQuery()
            ->select('p.*, c.name as category_name, c.slug as category_slug')
            ->join($category->getTable() . ' as c', 'c.id = p.category_id')
            ->order('p.created_at DESC');
    }

    public function findPublic(): QueryBuilder
    {
        return $this->findAll()
            ->where('p.published = 1')
            ->where('p.created_at < NOW()');
    }

    public function findPublicForCategory(int $id): QueryBuilder
    {
        return $this->findPublic()
            ->where("p.category_id = $id");
    }

    public function findWithCategory(int $postId): Post
    {
        return $this->findPublic()->where("p.id = $postId")->fetch();
    }
}
