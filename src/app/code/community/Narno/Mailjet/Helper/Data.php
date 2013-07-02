<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2011-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Narno_Mailjet_Helper_Data extends Mage_Core_Helper_Abstract
{
    const MAGENTO_WEBSITE_NAME_DEFAULT = 'Main Website';

    protected $_origin = '';

    /**
     * Subscriber origin
     *
     * @todo website or store group or store view?
     *
     * @return string
     */
    public function getSubscriberOrigin()
    {
        if (!$this->_origin) {
            $this->_origin = Mage::app()->getWebsite()->getName(); // Website name
            if ($this->_origin == self::MAGENTO_WEBSITE_NAME_DEFAULT) {
                $this->_origin = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
            }
            $this->_origin .= ' (' . Mage::app()->getWebsite()->getCode() . ')'; // Website code
        }
        return $this->_origin;
    }

    /**
     * Log debug helper
     *
     * @param string $message
     */
    public function logDebug($message)
    {
        $config = Mage::getSingleton('narno_mailjet/config');
        /* @var $config Narno_Mailjet_Model_Config */
        if ($config->isDebug()) {
            Narno_Mailjet_Model_Logger::logDebug($message);
        }
    }
    
    /**
     * Replace password by '*****' in array
     * 
     * @param array $array
     * @param string $password
     */
    public function hidePassword($array, $password)
    {
        $arrayWithoutPassword = array_replace($array, array_fill_keys(
            array_keys($array, $password),
            '*****'
        ));
        return $arrayWithoutPassword;
    }
}