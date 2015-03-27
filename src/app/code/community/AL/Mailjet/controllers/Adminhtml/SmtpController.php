<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Adminhtml_SmtpController
 */
class AL_Mailjet_Adminhtml_SmtpController
    extends Mage_Adminhtml_Controller_Action
{
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER    = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE  = 'contacts/email/email_template';

    /**
     * Return testing result
     *
     * @return void
     */
    public function testAction()
    {
        $postObject = new Varien_Object();
		$postObject->setName(Mage::helper('al_mailjet')->__('Mailjet Test Bot'));
		$postObject->setComment(Mage::helper('al_mailjet')->__('Test success!'));
        
        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template')
            ->setDesignConfig(array('area' => 'frontend'))
            ->sendTransactional(
                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
				null,
				array('data' => $postObject)
            );

        if (!$mailTemplate->getSentSuccess()) {
            $result = array(
                'status'  => 'error',
                'message' => Mage::helper('narno_mailjet')->__('Test fail!')
                    . ' (' . Mage::helper('narno_mailjet')->__('check exception.log file')
                    . ')',
            );
        }
        else {
            $result = array(
                'status'  => 'success',
                'message' => Mage::helper('al_mailjet')->__('Test success!'),
            );
        }

        $translate->setTranslateInline(true);

        Mage::app()->getResponse()
            ->setHeader('Content-Type', 'application/json', true)
            ->setBody(Mage::helper('core')->jsonEncode($result));
    }
}