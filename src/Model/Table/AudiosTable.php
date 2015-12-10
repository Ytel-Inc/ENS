<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AudiosTable extends Table
{

    public function initialize(array $config)
    {
        $this->primaryKey('audio_id');
    }
}