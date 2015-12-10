<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class NumberListsTable extends Table
{

    public function initialize(array $config)
    {
        $this->primaryKey('number_list_id');
    }
}