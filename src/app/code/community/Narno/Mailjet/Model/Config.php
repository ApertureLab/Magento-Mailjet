<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2011-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Narno Mailjet Model Config
 */
class Narno_Mailjet_Model_Config
{
    const XML_PATH_SETTINGS    = 'settings';
    const XML_PATH_AUTH_CONFIG = 'mailjet/authentication';
    const XML_PATH_API_CONFIG  = 'mailjet/api';

    /**
     * Return config $key item from config.xml > settings
     *
     * @param string $key
     * @return string
     */
    public static function getSettings($key)
    {
        $value = Mage::getConfig()->getNode(self::XML_PATH_SETTINGS . '/' . $key);
        if (!$value) {
            return false;
        }
        return (string) $value;
    }

    public static function isDebug()
    {
        return self::getSettings('debug');
    }

    public static function isDebugApi()
    {
        return self::getSettings('debug_api');
    }

    /**
     * Return authentication config $key item from core_config_data
     *
     * @param string $key
     * @return string
     */
    public static function getAuthConfig($key)
    {
        return Mage::getStoreConfig(self::XML_PATH_AUTH_CONFIG . '/' . $key);
    }

    /**
     * Return API config $key item from core_config_data
     *
     * @param string $key
     * @return string
     */
    public static function getApiConfig($key)
    {
        return Mage::getStoreConfig(self::XML_PATH_API_CONFIG . '/' . $key);
    }

    /**
     * Check whether Mailjet API credentials are available
     *
     * @return bool
     */
    public static function isApiAvailabe()
    {
        if ($this->getAuthConfig('apikey')
            && $this->getAuthConfig('secretkey')
            && $this->getApiConfig('listid')
        ) {
            return true;
        }
    }

    /**
     * Use proxy?
     *
     * @return bool
     */
    public static function isUseProxy()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_API_CONFIG . '/use_proxy');
    }

    /**
     * Module $moduleName is active?
     * 
     * Use to check dependencies
     * 
     * @param string $moduleName
     * @return bool 
     */
    public static function isModuleActive($moduleName)
    {
        $node = Mage::getConfig()->getNode('modules/' . $moduleName);
        if (is_object($node) && strval($node->active) == 'true') {
            return true;
        }
        return false;
    }
}