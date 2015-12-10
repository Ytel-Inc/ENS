<?php

namespace App\Shell\Task;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Ytel\Message360;
use Ytel\Message360_Exception;

require_once(ROOT.DS.'vendor'.DS.'message360'.DS.'message360.php');

class M360Task extends Shell
{

    public function sendSms($toNumber, $message, $toCountryCode = 1)
    {
        $Message360 = Message360::getInstance();

        $Message360->setOptions([
            'account_sid' => Configure::read('Message360.accountSid'),
            'auth_token' => Configure::read('Message360.authToken'),
            'response_to_array' => true,
        ]);

        try {
            // Fetch Send SMS
            $sendSMS = $Message360->create('sms', 'sendsms',
                [
                'FromCountryCode' => 1, //required
                'From' => Configure::read('Message360.fromNumber'), //required
                'ToCountryCode' => $toCountryCode, //required
                'To' => $toNumber, //required
                'Body' => $message, //required
                'Method' => 'POST', //Ex.POST or GET  //optional
            ]);

            return $sendSMS->getResponse();
        } catch (Message360_Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function sendCall($toNumber, $uniqueId, $toCountryCode = 1)
    {
        $Message360 = Message360::getInstance();

        $Message360->setOptions([
            'account_sid' => Configure::read('Message360.accountSid'),
            'auth_token' => Configure::read('Message360.authToken'),
            'response_to_array' => true,
        ]);

        try {
            // Fetch Send SMS
            $sendSMS = $Message360->create('calls', 'makeCall',
                [
                'FromCountryCode' => 1, //required
                'From' => Configure::read('Message360.fromNumber'), //required
                'ToCountryCode' => $toCountryCode, //required
                'To' => $toNumber, //required
                'Method' => 'POST', //Ex.POST or GET  //optional
                'Url' => 'https://'.Configure::read('Message360.systemResponseDomain').'/Call/ajaxResponseCall/'.$uniqueId.'.xml',
                'FallbackUrl' => 'https://'.Configure::read('Message360.systemResponseDomain').'/Call/ajaxResponseCallFallback/'.$uniqueId.'.json',
                'StatusCallback' => 'https://'.Configure::read('Message360.systemResponseDomain').'/Call/ajaxResponseStatusCallback/'.$uniqueId.'.json',
            ]);

            return $sendSMS->getResponse();
        } catch (Message360_Exception $ex) {
            return $ex->getMessage();
        }
    }
}