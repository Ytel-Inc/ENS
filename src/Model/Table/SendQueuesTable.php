<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class SendQueuesTable extends Table
{

    public function initialize(array $config)
    {
        $this->primaryKey('send_queue_id');
        $this->belongsTo('Audios');
    }
}