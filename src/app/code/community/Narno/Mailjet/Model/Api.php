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
    protected $_api = null;

    /**
     * Return Zend_Service_Mailjet object
     * 
     * @param string $apikey
     * @param string $secretkey
     * @return \Zend_Service_Mailjet
     */
    protected function _getApi($apikey='', $secretkey='', $force=false)
    {
        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if (empty($apikey) || empty($secretkey)) {
            $apikey    = $config->getAuthConfig('apikey');
            $secretkey = $config->getAuthConfig('secretkey');
        }

        if (is_null($this->_api) || $force === true) {
            try {
                // use proxy?
                if ($config->isUseProxy()) {
                    if ($config->getApiConfig('proxy_host') && $config->getApiConfig('proxy_port')) {
                        $proxyConfig = array(
                            'proxy_host' => $config->getApiConfig('proxy_host'),
                            'proxy_port' => $config->getApiConfig('proxy_port'),
                        );
                        $httpAdapter = new Zend_Http_Client_Adapter_Proxy();
                        $httpAdapter->setConfig($proxyConfig);
                        $httpClient = new Zend_Http_Client();
                        $httpClient->setAdapter($httpAdapter);
                        $this->_api = new Zend_Service_Mailjet($apikey, $secretkey, $httpClient);
                    }
                }
                else {
                    $this->_api = new Zend_Service_Mailjet($apikey, $secretkey);
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        return $this->_api;
    }

    /**
     * API connection test
     * 
     * @param string $apikey API key
     * @param string $secretkey Secret key
     * @param integer $list List ID
     * @return boolean
     */
    public function testConnection($apikey, $secretkey, $list)
    {
        try {
            $response = $this->_getApi($apikey, $secretkey)
                ->lists->email(array(
                    'id' => $list
                ));
            if ($response->status == 'OK') {
                return true;
            }
            return false;
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
        return false;
    }
}