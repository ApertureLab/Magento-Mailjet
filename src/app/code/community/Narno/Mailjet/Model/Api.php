<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2010-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Narno Mailjet Api model
 */
class Narno_Mailjet_Model_Api extends Varien_Object
{
    protected function _getApi($apikey='', $secretkey='')
    {
        if (empty($apikey) || empty($secretkey)) {
            $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */
            if (empty($apikey)) {
                $apikey = $config->getAuthConfig('apikey');
            }
            if (empty($secretkey)) {
                $secretkey = $config->getAuthConfig('secretkey');
            }
        }

        return new Zend_Service_Mailjet($apikey, $secretkey);
    }
}