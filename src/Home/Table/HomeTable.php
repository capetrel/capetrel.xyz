<?php
namespace App\Home\Table;

use App\Framework\Database\Table;
use App\Home\Entity\Home;

class HomeTable extends Table
{
    protected $entity = Home::class;

    protected $table = 'proverbs';

    public function randomEntry()
    {
        $results = $this->findAll()->fetchAll();
        $proverbs = $results->toArray();
        $max = count($proverbs);
        $random = rand(1, $max);

        return $this->findBy('id', "$random");
    }

}
