<?php
namespace Tests\Basket\Table;

use App\Basket\Basket;
use App\Basket\Entity\OrderEntity;
use App\Basket\Table\OrderRowTable;
use App\Basket\Table\OrderTable;
use App\Shop\Table\ProductTable;
use Tests\DatabaseTestCase;

class OrderTableTest extends DatabaseTestCase
{

    /**
     * @var OrderTable
     */
    private $orderTable;

    /**
     * @var OrderRowTable
     */
    private $orderRowTable;

    /**
     * @var ProductTable
     */
    private $productTable;

    protected function setUp()
    {
        $pdo = $this->getPdo();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);
        $this->orderTable = new OrderTable($pdo);
        $this->orderRowTable = new OrderRowTable($pdo);
        $this->productTable = new ProductTable($pdo);
    }

    public function testCreateFromBasket()
    {
        $products = $this->productTable->makeQuery()->limit(10)->fetchAll();
        $basket = new Basket();
        $basket->addProduct($products[0]);
        $basket->addProduct($products[1], 2);
        $this->orderTable->createFromBasket($basket, [
            'country' => 'fr',
            'tax' => 0,
            'user_id' => 1,
        ]);

        /** @var OrderEntity $order */
        $order = $this->orderTable->find(1);
        $this->assertEquals($basket->getTotal(), $order->getPrice());
        $this->assertEquals(2, $this->orderRowTable->count());
        return $order;
    }

    public function testFindRows()
    {
        $order = $this->testCreateFromBasket();
        $this->orderTable->findRows([$order]);
        $this->assertCount(2, $order->getRows());
    }
}
