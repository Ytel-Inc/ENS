<?php
/**
 * Ytel Emergency Notification System
 *
 * @copyright Ytel, Inc (http://www.ytel.com)
 * @link      http://www.ytel.com
 * @since     0.1
 */

namespace App\Controller;

//use Cake\Core\Configure;
//use Cake\Network\Exception\NotFoundException;
//use Cake\View\Exception\MissingTemplateException;

class AdminController extends AppController
{

    public function index()
    {
        $this->viewBuilder()->layout('ens');
    }
}