<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Helper_Data
 */
class AL_Mailjet_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Log debug helper
     *
     * @param string $message
     */
    public function logDebug($message)
    {
        $config = Mage::getSingleton('al_mailjet/config');
        /* @var $config AL_Mailjet_Model_Config */
        if ($config->isDebug()) {
            AL_Mailjet_Model_Logger::logDebug($message);
        }
    }
}