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
}