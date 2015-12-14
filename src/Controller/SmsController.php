<?php
/**
 * Ytel Emergency Notification System
 *
 * @copyright Ytel, Inc (http://www.ytel.com)
 * @link      http://www.ytel.com
 * @since     0.1
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Utility\Text;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

class SmsController extends AppController
{

//    public function initialize()
//    {
//        parent::initialize();
//        $this->loadComponent('M360');
//    }

    public function ajaxSendSmsMessage()
    {
        try {
            $numberListTable = TableRegistry::get('NumberLists');
            $sendQueueTable = TableRegistry::get('SendQueues');
            $numberTable = TableRegistry::get('Numbers');

            // Check if list exists
            $numberList = $numberListTable->find('all',
                [
                'conditions' => [
                    'number_list_id' => $this->request->data['sms']['number_list_id']
                ]
            ]);

            if (!$numberList->count()) {
                throw new \Exception('Cannot locate list.');
            }

            $number = $numberTable->find('all', [
                'conditions' => [
                    'number_list_id' => $this->request->data['sms']['number_list_id']
                ]
            ]);

            $numberCount = $number->count();
            if( !$numberCount ) {
                throw new \Exception('No numbers in the list');
            }

            if (!isset($this->request->data['sms']['message'])) {
                throw new \Exception('Missing message.');
            }

            $this->request->data['sms']['message'] = filter_var($this->request->data['sms']['message'], FILTER_SANITIZE_STRING);

            $data = [
                'unique_id' => Text::uuid(),
                'type' => 1,
                'number_list_id' => $this->request->data['sms']['number_list_id'],
                'status' => 0,
                'message' => $this->request->data['sms']['message'],
                'request_by' => null,
                'total' => $numberCount,
                'create_datetime' => new \DateTime('now', new \DateTimeZone('UTC'))
            ];
            $sendQueue = $sendQueueTable->newEntity($data);

            $sendQueueTable->save($sendQueue);

            // Hit the cron
            shell_exec(ROOT.DS.'bin'.DS.'cake SendSms '.$sendQueue->send_queue_id.' > /dev/null 2>/dev/null &');

            $response['status'] = 1;
            $response['sendQueueId'] = $sendQueue->send_queue_id;
            $response['numberCount'] = $numberCount;
        } catch (\Exception $ex) {
            $response['status'] = 0;
            $response['message'] = $ex->getMessage();
        }


        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

//    public function
}