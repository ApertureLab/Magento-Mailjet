<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Adminhtml_ApiController
 */
class AL_Mailjet_Adminhtml_ApiController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Return testing result
     *
     * @return void
     */
    public function testAction()
    {
        // Get params
        $apikey    = $this->getRequest()->getParam('apikey');
        $secretkey = $this->getRequest()->getParam('secretkey');
        $listid    = $this->getRequest()->getParam('listid');

        if (empty($apikey) || empty($secretkey) || empty($listid)) {
            $result = array(
                'status'  => 'notice',
                'message' => 'API key, Secret key and List ID should be filled.',
            );
        }
        // try to connect to API
        else {    
            $api = Mage::getSingleton('al_mailjet/api');
            /* @var $api AL_Mailjet_Model_Api */
            $test = $api->testConnection($apikey, $secretkey, $listid);
            if ($test !== true) {
                $result = array(
                    'status'  => 'error',
                    'message' => 'Unable to connect to the API.',
                );
                if (is_string($test) && AL_Mailjet_Model_Config::isDebug()) {
                    $result = array(
                        'status'  => 'error',
                        'message' => $test,
                    );
                }
            }
            else {
                $result = array(
                    'status'  => 'success',
                    'message' => 'Connection success!',
                );
            }
        }

        Mage::app()->getResponse()
            ->setHeader('Content-Type', 'application/json', true)
            ->setBody(Mage::helper('core')->jsonEncode($result));
    }
}