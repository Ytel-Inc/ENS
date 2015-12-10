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

class ListController extends AppController
{

    public function ajaxListSelect()
    {
        $numberList = TableRegistry::get('NumberLists');

        $response = $numberList->find('all', [
            'fields' => [
                'number_list_id',
                'list_name'
            ],
            'conditions' => [
                'remove_datetime IS NULL'
            ],
            'order' => [
                'list_name'
            ]
        ]);

        $this->set(compact('response'));
		$this->set('_serialize', ['response']);
    }

    public function ajaxCountryPhoneCodeSelect()
    {
        $country = TableRegistry::get('Countries');

        $response = $country->find('all', [
            'fields' => [
                'country_id',
                'phone_code',
                'name'
            ],
            'order' => [
                'country_id'
            ]
        ]);

        $this->set(compact('response'));
		$this->set('_serialize', ['response']);
    }
}