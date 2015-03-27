<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Model_Config
 */
class AL_Mailjet_Model_Config
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
     * Check if API is enabled
     * 
     * @return boolean
     */
    public static function isApiEnabled()
    {
        if (self::getApiConfig('enabled') == '1') {
            return true;
        }
        return false;
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