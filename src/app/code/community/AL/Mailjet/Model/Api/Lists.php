<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Model_Api_Lists
 */
class AL_Mailjet_Model_Api_Lists
    extends AL_Mailjet_Model_Api
{
    /**
     * Mailjet subscription (add) by email
     * Return new contact ID
     *
     * @param string $email
     * @param integer $list
     * @return integer|boolean
     */
    public function subscribe($email, $list)
    {
        Mage::helper('narno_mailjet')->logDebug('Subscribe: ' . $email); // debug
        try {
            $params = array(
                'contact' => $email,
                'id'      => $list,
                'force'   => true,
            );
            Mage::helper('al_mailjet')->logDebug(array('params' => $params)); // debug
            $response = $this->_getApi()->lists->addcontact($params);
            Mage::helper('al_mailjet')->logDebug('ID: ' . $response->contact_id); // debug
            return $response->contact_id;
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
    
    /**
     * Mailjet unsubscription by email
     *
     * @param string $email
     * @param integer $list
     * @return boolean
     */
    public function unsubscribe($email, $list)
    {
        Mage::helper('al_mailjet')->logDebug('Unsubscribe: ' . $email); // debug
        try {
            $params = array(
                'contact' => $email,
                'id'      => $list,
            );
            Mage::helper('al_mailjet')->logDebug(array('params' => $params)); // debug
            $response = $this->_getApi()->lists->unsubcontact($params);
            return true;
        } catch (Zend_Http_Client_Exception $e) {
            AL_Mailjet_Model_Logger::logException($e);
            AL_Mailjet_Model_Logger::logSubscriptionError($e, $email, __METHOD__);
            return false;
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
    }
}