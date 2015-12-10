<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class NumbersTable extends Table
{

    public function initialize(array $config)
    {
        $this->primaryKey('number_id');
    }
}