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
use Cake\Controller\Component\M360;

class NumberController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('M360');
    }

    public function addNumber()
    {
        try {
            $countryTable = TableRegistry::get('Countries');
            $numberTable = TableRegistry::get('Numbers');
            
            $country = $countryTable->get($this->request->data['country_id']);

            $dateTimeUtc = new \DateTimeZone('UTC');
            $now = new \DateTime('now', $dateTimeUtc);

            $responseM360 = $this->M360->optInTfn($this->request->data['phone_number']);


            $data = [
                'number_list_id' => $this->request->data['number_list_id'],
                'country_code' => $country->phone_code,
                'phone_number' => $this->request->data['phone_number'],
                'opt_in_tfn' => (bool) $responseM360['Message360']['OptIns']['OptIn']['Status'] === 'updated',
                'add_datetime' => $now
            ];
            $number = $numberTable->newEntity($data);
            $numberTable->save($number);


            $response['status'] = 1;
        } catch (\Excaption $ex) {
            $response['status'] = 0;
            $response['message'] = $ex->getMessage();
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function upload()
    {
        ini_set("auto_detect_line_endings", true);
        ini_set('memory_limit', '512M');
        set_time_limit(600);

        require_once(ROOT.DS.'vendor'.DS.'parsecsv.lib.php');


        try {

            $numberListTable = TableRegistry::get('NumberLists');
            $numberTable = TableRegistry::get('Numbers');

            // Generate temp file name
            $uploadFullPath = TMP.DS.Text::uuid();

            // Move file to temp location
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFullPath)) {
                throw new \Exception('Cannot copy file to tmp location');
            }

            $dateTimeUtc = new \DateTimeZone('UTC');
            $now = new \DateTime('now', $dateTimeUtc);

            // Create new list
            $newNumberListData = [
                'list_name' => $this->request->data['list_name'],
                'list_description' => $this->request->data['list_description'],
                'create_datetime' => $now,
                'update_datetime' => $now
            ];
            $numberList = $numberListTable->newEntity($newNumberListData);
            $numberListTable->save($numberList);

            // Get the data from the file
            $csv = new \parseCSV();
            $csv->heading = false;
            $csv->auto($uploadFullPath);

            if (count($csv->data) == 0) {
                throw new \Exception('File is empty');
            }
            $newNumberData = [];
            foreach ($csv->data as $row) {

                $responseM360 = $this->M360->optInTfn($row[1]);

//                $response['d'][] = $responseM360;

                $newNumberData = [
                    'number_list_id' => $numberList->number_list_id,
                    'country_code' => $row[0],
                    'phone_number' => $row[1],
                    'opt_in_tfn' => (bool) $responseM360['Message360']['OptIns']['OptIn']['Status'] === 'updated',
                    'add_datetime' => $now
                ];

                $number = $numberTable->newEntity($newNumberData);
                $numberTable->save($number);
            }
//            $numbers = $numberTable->newEntity($newNumberData);
//
//            $numberTable->connection()->transactional(function () use ($numberTable, $numbers) {
//                foreach ($numbers as $number) {
//                    $numberTable->save($number, ['atomic' => false]);
//                }
//            });

            unlink($uploadFullPath);

            $response['i'] = $newNumberData;

            $response['status'] = 1;
        } catch (\Excaption $ex) {
            $response['status'] = 0;
            $response['message'] = $ex->getMessage();
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}