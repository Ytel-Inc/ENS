<?php

namespace App\Shell;

require_once(ROOT.DS.'vendor'.DS.'autoload.php');

use Cake\Core\Configure;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SendCallBackgroundShell extends Shell
{
    public $tasks = ['M360'];

    public function main()
    {
        try {
            $DEFAULT_URL = 'https://ens.firebaseio.com/';
            $DEFAULT_TOKEN = Configure::read('Firebase.token');
            $DEFAULT_PATH = '/call';

            $firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $DEFAULT_TOKEN);

            if( !isset($this->args[0]) ) {
                throw new \Exception('Missing queue ID');
            }

            if( !isset($this->args[1]) ) {
                throw new \Exception('Missing number ID');
            }

            if( !isset($this->args[2]) ) {
                throw new \Exception('Missing country code');
            }

            if( !isset($this->args[3]) ) {
                throw new \Exception('Missing phone number');
            }

            if( !isset($this->args[4]) ) {
                throw new \Exception('Missing unique ID');
            }

            list($queueId, $numberId, $countryCode, $phoneNumber, $uniqueId) = $this->args;

            // Set to pending...
            $firebase->set($DEFAULT_PATH.'/'.$queueId.'/numbers/'.$numberId.'/status', 2);

            $response = $this->M360->sendCall($phoneNumber, $uniqueId, $countryCode);
            
            if ($response['Message360']['ResponseStatus'] && $response['Message360']['Call'][0]['Status'] === 'In-queue') {
                // Set to sent...
                $firebase->set($DEFAULT_PATH.'/'.$queueId.'/number_match/'.$response['Message360']['Call'][0]['CallSid'], (int)$numberId);
                $firebase->set($DEFAULT_PATH.'/'.$queueId.'/numbers/'.$numberId.'/status', 3);

            } else {
                // Set to fail...
                $firebase->set($DEFAULT_PATH.'/'.$queueId.'/numbers/'.$numberId.'/status', 4);
            }
        } catch (\Exception $ex) {
            $this->out($ex->getMessage());
        }
    }
}