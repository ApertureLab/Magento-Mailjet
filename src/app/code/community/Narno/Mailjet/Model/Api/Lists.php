<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2010-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Narno Mailjet Api Lists Model
 */
class Narno_Mailjet_Model_Api_Lists
    extends Narno_Mailjet_Model_Api
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
            Mage::helper('narno_mailjet')->logDebug(array('params' => $params)); // debug
            $response = $this->_getApi()->lists->addcontact($params);
            Mage::helper('narno_mailjet')->logDebug('New ID: ' . $response->contact_id); // debug
            return $response->contact_id;
        } catch (Zend_Http_Client_Exception $e) {
            Narno_Mailjet_Model_Logger::logException($e);
            Narno_Mailjet_Model_Logger::logSubscriptionError($e, $email, __METHOD__);
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
        Mage::helper('narno_mailjet')->logDebug('Unsubscribe: ' . $email); // debug
        try {
            $params = array(
                'contact' => $email,
                'id'      => $list,
            );
            Mage::helper('narno_mailjet')->logDebug(array('params' => $params)); // debug
            $response = $this->_getApi()->lists->unsubcontact($params);
            return true;
        } catch (Zend_Http_Client_Exception $e) {
            Narno_Mailjet_Model_Logger::logException($e);
            Narno_Mailjet_Model_Logger::logSubscriptionError($e, $email, __METHOD__);
            return false;
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
    }
}