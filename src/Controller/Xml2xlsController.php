<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use Cake\Utility\Xml;

ini_set('memory_limit', '3048M');

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class Xml2xlsController extends AppController
{
    public function index() {
        
    }
    
    public function upload() {
        
        if ($this->request->is('post') && !empty($this->request->data['Xml2xls']['xmlfile']['name']) ) {
            
            $file = $this->request->data['Xml2xls']['xmlfile'];
            $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
            
            if(in_array($ext, ['xml'])) {
                
                if( $xmlObject = Xml::build(file_get_contents($file['tmp_name'])) ) {

                    $xmlArray = XML::toArray($xmlObject);
                    
                    if(is_array($xmlArray)) {
                        $itemkey = empty($this->request->data['Xml2xls']['itemkey']) ? 'offer' : $this->request->data['Xml2xls']['itemkey'];

                        $xmlArray = $this->_findCatalogItem($xmlArray, $itemkey);

                        if($xmlArray && !empty($xmlArray)) {

                            set_time_limit(240);

                            $array_all_keys = [];

                            foreach( $xmlArray as $key => $offer) {

                                /**
                                 * Convert arrays to dimensional array
                                 */
                                foreach($offer as $prop_key => $prop) {
                                    if (is_array($prop)) {
                                         if (!empty($prop)) {
                                            // parse params
                                            foreach($prop as $param) {
                                                if(!empty($param['@name'])) {
                                                    $xmlArray[$key][$param['@name']] = empty($param['@']) ? '' : $param['@'];
                                                    if(!empty($param['@unit'])) {
                                                        $xmlArray[$key][$param['@name']] .= ' ' . $param['@unit'];
                                                    }
                                                }
                                            }
                                         }
                                        unset($xmlArray[$key][$prop_key]);
                                    }
                                }
                                // save absent keys
                                foreach($xmlArray[$key] as $item_key => $item_value) {
                                    if(!in_array($item_key, $array_all_keys)) {
                                        $array_all_keys[] = $item_key;
                                    }
                                }
                            }

                            // fill missing keys & sort rows
                            foreach($xmlArray as $k => $item) {

                                foreach($array_all_keys as $key) {
                                    if(!isset($xmlArray[$k][$key])) {
                                        $xmlArray[$k][$key] = '';
                                    }
                                }

                                ksort($xmlArray[$k]);
                            }

                            $this->set('data', $xmlArray );

                            $this->viewBuilder()->className('CakeExcel.Excel');

                            return;
                        } else {
                            $this->Flash->error( __('Search key not found in the XML'));
                        }
                    } else {
                        $this->Flash->error( __('Can`t convert XML to array'));
                    }
                } else {
                    $this->Flash->error( __('Invalid XML'));
                }
            } else {
                $this->Flash->error( __('This is not XML file'));
            }   
        } else {
            $this->Flash->error( __('Invalid or empty request: '.print_r($this->request->data) ));
        }

        return $this->redirect('/xml2xls');
    }
    
    protected function _findCatalogItem(Array $data, $key) {

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        foreach ($data as $item) {
            if (is_array($item)) {
                if ($catalog = $this->_findCatalogItem($item, $key)) {
                    return $catalog;
                }
            }
        }

        return false;
    }
}
