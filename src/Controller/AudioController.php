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

class AudioController extends AppController
{

    public function upload()
    {
        try {
            $audioTable = TableRegistry::get('Audios');

            $dateTimeUtc = new \DateTimeZone('UTC');
            $now = new \DateTime('now', $dateTimeUtc);

            // Generate file name
            $serverDir = TMP.'files'.DS;
            $serverFileName = Text::uuid().'.wav';
            $uploadFullPath = $serverDir.$serverFileName;

            // Move file to temp location
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFullPath)) {
                throw new \Exception('Cannot copy file to tmp location: '.$uploadFullPath);
            }

            $uploadFullPath = $this->__convertToPhoneAudio($uploadFullPath);

            $data = [
                'file_name' => $_FILES['file']['name'],
                'server_dir' => $serverDir,
                'server_name' => $serverFileName,
                'create_datetime' => $now
            ];
            $audio = $audioTable->newEntity($data);
            $audioTable->save($audio);

            $response['status'] = 1;
            $response['audioId'] = $audio->audio_id;
        } catch (\Excaption $ex) {
            $response['status'] = 0;
            $response['message'] = $ex->getMessage();
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function ajaxList()
    {
        $audioTable = TableRegistry::get('Audios');

        $response = $audioTable->find('all',
                [
                'fields' => [
                    'audio_id',
                    'file_name',
                    'create_datetime'
                ],
                'conditions' => [
                    'remove_datetime IS NULL'
                ]
            ])->all();

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function __convertToPhoneAudio($file)
    {
        $ffmpegbin = Configure::read('Audio.ffmpegbin');
        $filebin = Configure::read('Audio.filebin');
        $sox = Configure::read('Audio.sox');

        if (file_exists($file) && file_exists($ffmpegbin)) {
            // test file type
            $cmd = $filebin." -b ".$file;
            exec($cmd, $dumpout, $exittype);
            $dumpout = implode(" ", $dumpout);
            if ($exittype > 0) {
                $message = "Failed to determine file type ".$cmd." ".$dumpout;
                throw new \Exception($message);
            } else if (preg_match("/^data|mono 8000 H/i", $dumpout)) { // probably is unknown audio like gsm or already converted
                return($file);
            } else { // try to convert file..
                $cmd = $ffmpegbin." -i ".$file." -ar 8000 -ac 1 -ab 32 -acodec pcm_s16le ".$file."X.wav 2>&1";
                exec($cmd, $dumpout, $exittype);
                if ($exittype > 0) {
                    $message = "Failed to convert audio file ".$cmd." ".json_encode($dumpout);
                    throw new \Exception($message);
                }

                $cmd = $sox." {$file}X.wav -e signed-integer {$file}XX.wav";
                exec($cmd, $dumpout, $exittype);
                if ($exittype > 0) {
                    $message = "Failed to convert update file type ".$cmd." ".json_encode($dumpout);
                    throw new \Exception($message);
                } else {
                    unlink($file."X.wav");
                }

                $newfilename = preg_replace('/\.\w+$/', '', $file);
                $newfilename = $newfilename.".wav";
                unlink($file);
                if (!rename($file."XX.wav", $newfilename)) {
                    $message = "Failed to rename file!";
                    throw new \Exception($message);
                }

                return($newfilename);
            }
        } else {
            throw new \Exception('Missing ffmpegbin');
        }
    }
}