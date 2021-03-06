<?php
namespace Tests\Framework\Database;

use App\Framework\Database\QueryBuilder;
use Tests\DatabaseTestCase;

class QueryTest extends DatabaseTestCase
{

    public function testSimpleQuery()
    {
        $query = (new QueryBuilder())->from('portfolio')->select('name');
        $this->assertEquals('SELECT name FROM portfolio', (string)$query);
    }

    public function testQueryWithWhere()
    {
        $query = (new QueryBuilder())
            ->from('portfolio', 'p')
            ->where('a = :a OR b = :b', 'c = :c');

        $query2 = (new QueryBuilder())
            ->from('portfolio', 'p')
            ->where('a = :a OR b = :b')
            ->where('c = :c');

        $this->assertEquals('SELECT * FROM portfolio as p WHERE (a = :a OR b = :b) AND (c = :c)', (string)$query);
        $this->assertEquals('SELECT * FROM portfolio as p WHERE (a = :a OR b = :b) AND (c = :c)', (string)$query2);
    }

    public function testFetchAll()
    {
        $pdo = $this->getPdo();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);

        $posts = (new QueryBuilder($pdo))
            ->from('portfolio', 'p')
            ->count();

        $this->assertEquals(100, $posts);

        $posts = (new QueryBuilder($pdo))
            ->from('portfolio', 'p')
            ->where('p.id < :number')
            ->params(['number' => 30])
            ->count();

        $this->assertEquals(29, $posts);
    }

    public function testHydrateEntity()
    {
        $pdo = $this->getPdo();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);

        $posts = (new QueryBuilder($pdo))
            ->from('portfolio', 'p')
            ->where('p.id < :number')
            ->params(['number' => 5])
            ->into(Demo::class)
            ->fetchAll();

        $this->assertEquals('demo', substr($posts[0]->getSlug(), -4));
    }

    public function testLazyHydrate()
    {
        $pdo = $this->getPdo();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);

        $posts = (new QueryBuilder($pdo))
            ->from('portfolio', 'p')
            ->into(Demo::class)
            ->fetchAll();

        $post = $posts[0];
        $post2 = $posts[0];

        $this->assertSame($post, $post2);
    }

    public function testJoinQuery()
    {
        $query = (new QueryBuilder())
            ->from('portfolio', 'p')
            ->select('name')
            ->join('types as c', 'c.id = p.category_id')
            ->join('types as c2', 'c2.id = p.category_id', 'inner');
        $this->assertEquals('SELECT name FROM'.
            ' portfolio as p'.
            ' LEFT JOIN types as c ON c.id = p.category_id'.
            ' INNER JOIN types as c2 ON c2.id = p.category_id', (string)$query);
    }

    public function testOrderLimitQuery()
    {
        $query = (new QueryBuilder())
            ->from('portfolio', 'p')
            ->select('name')
            ->order('id DESC')
            ->order('name ASC')
            ->limit(10, 5);
        $this->assertEquals('SELECT name FROM portfolio as p ORDER BY id DESC, name ASC LIMIT 5, 10', (string)$query);
    }
}
