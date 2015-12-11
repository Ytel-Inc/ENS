<?php

namespace App\Shell;

require_once(ROOT.DS.'vendor'.DS.'autoload.php');

use Cake\Core\Configure;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SendSmsShell extends Shell
{
    public $tasks = ['M360'];

    public function main()
    {
        try {
            $DEFAULT_URL = 'https://ens.firebaseio.com/';
            $DEFAULT_TOKEN = Configure::read('Firebase.token');
            $DEFAULT_PATH = '/sms';

            $firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $DEFAULT_TOKEN);

            if (!isset($this->args[0])) {
                throw new \Exception('Missing queue ID');
            }

            $this->out('Start...');

            $sendQueueTable = TableRegistry::get('SendQueues');
            $numberTable = TableRegistry::get('Numbers');

            $dateTimeUtc = new \DateTimeZone('UTC');
            $now = new \DateTime('now', $dateTimeUtc);

            $sendQueue = $sendQueueTable->find('all',
                [
                'conditions' => [
                    'type' => 1,
                    'send_queue_id' => $this->args[0],
//                    'status' => 0,
                    'OR' => [
                        'next_try_datetime IS NULL',
                        'next_try_datetime <=' => $now
                    ]
                ]
            ]);
            if (!$sendQueue->count()) {
                throw new \Exception('No more queue');
            }


            $firstQueue = $sendQueue->first();

            // Mark as being processed...
            $firebase->set($DEFAULT_PATH.'/'.$firstQueue->send_queue_id.'/status', 1);
            $firstQueue->status = 1;
            $firstQueue->start_datetime = $now;
            $sendQueueTable->save($firstQueue);

            $this->out(json_encode($firstQueue));

            $numbers = $numberTable->find('all',
                [
                'fields' => [
                    'number_id',
                    'number_list_id',
                    'country_code',
                    'phone_number'
                ],
                'conditions' => [
                    'number_list_id' => $firstQueue->number_list_id
                ]
            ]);

            // Format the number for Firebase
            $numberForFb = Hash::combine($numbers->toArray(), '{n}.number_id', '{n}');

            // Put the all the number on Firebase
            $firebase->set($DEFAULT_PATH.'/'.$firstQueue->send_queue_id.'/numbers', $numberForFb);

            $this->out(json_encode($numbers));

            // Mark as sending...
            $firebase->set($DEFAULT_PATH.'/'.$firstQueue->send_queue_id.'/status', 2);
            $firstQueue->status = 2;
            $sendQueueTable->save($firstQueue);

            // Send SMS
            foreach ($numbers as $number) {
                shell_exec(ROOT.DS.'bin'.DS.'cake SendSmsBackground '.$firstQueue->send_queue_id.' '.$number->number_id.' '.$number->country_code.' '.$number->phone_number.' "'.$firstQueue->message.'" > /dev/null 2>/dev/null &');
            }

            // Mark as done...
            $firebase->set($DEFAULT_PATH.'/'.$firstQueue->send_queue_id.'/status', 3);
            $firstQueue->status = 3;
            $firstQueue->end_datetime = new \DateTime('now', $dateTimeUtc);
            $sendQueueTable->save($firstQueue);
        } catch (\Exception $ex) {
            $this->out($ex->getMessage());
        }
    }
}