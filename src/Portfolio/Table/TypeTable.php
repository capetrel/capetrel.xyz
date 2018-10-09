<?php
namespace App\Portfolio\Table;

use App\Framework\Database\Table;
use App\Portfolio\Entity\Type;

class TypeTable extends Table
{

    protected $entity = Type::class;

    protected $table = 'types';
}
