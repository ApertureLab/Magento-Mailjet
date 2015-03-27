<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Model_Api_Contact
 */
class AL_Mailjet_Model_Api_Contact
    extends AL_Mailjet_Model_Api
{
    public function getStatus($email, $list)
    {
        try {
            $params = array(
                'contact' => $email,
            );
            $response = $this->_getApi()->contact->infos($params);
            if ($response->status == 'OK') {
                $lists = $response->lists;
                foreach ($lists as $line) {
                    if ($line->list_id == $list) {
                        return $line->unsub;
                        exit();
                    }
                }
            }
        } catch (Zend_Http_Client_Exception $e) {
            AL_Mailjet_Model_Logger::logException($e);
            AL_Mailjet_Model_Logger::logSubscriptionError($e, $email, __METHOD__);
            return false;
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
        return false;
    }
}