<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2011-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Narno_Mailjet_Model_Template extends Mage_Core_Model_Email_Template
{
    /**
     * Send mail to recipient
     *
     * @param string $email E-mail
     * @param string|null $name receiver name
     * @param array $variables template variables
     * @return boolean
     */
    public function send($email, $name = null, array $variables = array())
    {
        // use default send method
        if (Mage::getStoreConfigFlag('mailjet/smtp/enabled') !== TRUE) {
            return parent::send($email, $name, $variables);
        }

        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        ini_set('SMTP', Mage::getStoreConfig('mailjet/smtp/host'));
        ini_set('smtp_port', Mage::getStoreConfig('mailjet/smtp/port'));

        $mail = $this->getMail();

        $transport = new Zend_Mail_Transport_Smtp(
            Mage::getStoreConfig('mailjet/smtp/host'),
            array(
                'port'     => Mage::getStoreConfig('mailjet/smtp/port'),
                'auth'     => 'login',
                'username' => Mage::getStoreConfig('mailjet/authentication/apikey'),
                'password' => Mage::getStoreConfig('mailjet/authentication/secretkey'),
                'ssl'      => Mage::getStoreConfig('mailjet/smtp/ssl'),
            )
        );
        Zend_Mail::setDefaultTransport($transport);

        foreach ($emails as $key => $email) {
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        if ($this->isPlain()) {
            $mail->setBodyText($text);
        }
        else {
            $mail->setBodyHTML($text);
        }

        $mail->addHeader ('X-Mailer', 'Magento_Narno_Mailjet', TRUE);
        $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        try {
            Mage::dispatchEvent('Narno_Mailjet_email_before_send', array(
                'mail'     => $mail,
                'template' => $this->getTemplateId(),
                'subject'  => $this->getProcessedTemplateSubject($variables),
            ));
            $mail->send();
            Mage::dispatchEvent('Narno_Mailjet_email_after_send', array(
                'mail'     => $mail,
                'template' => $this->getTemplateId(),
                'subject'  => $this->getProcessedTemplateSubject($variables),
            ));
            $this->_mail = null;
        }
        catch (Exception $e) {
            $this->_mail = null;
            Mage::logException($e);
            return false;
        }

        return true;
    }
}