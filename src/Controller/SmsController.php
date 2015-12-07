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
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
//use App\Controller\Component\Message360Component;

class SmsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('M360');
    }

    public function ajaxSendSmsMessage()
    {
        $numberTable = TableRegistry::get('Numbers');

//        $numbers = $numberTable->find('all',
//            [
//            'conditions' => [
//                'number_list_id' => $this->request->data['sms']['number_list_id']
//            ]
//        ]);
        $this->loadComponent('M360');
        $response = $this->M360->sendSms('3108958178', 'Test...');

        $this->set(compact('response'));
		$this->set('_serialize', ['response']);
    }
}