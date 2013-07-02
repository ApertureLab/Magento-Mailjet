<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2011-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Narno_Mailjet_Block_Adminhtml_Form_Testsmtpbutton
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('system/config/form/smtpbutton.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return AJAX URL for button
     *
     * @return string
     */
    public function getAjaxTestUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/smtp/test');
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'narno_mailjet_smtp_button',
            'label'     => $this->helper('adminhtml')->__('Sending test'),
            'onclick'   => 'javascript:smtp_test(); return false;'
        ));

        return $button->toHtml();
    }
    
    /**
     * Retrieve block view from file (template)
     *
     * @param   string $fileName
     * @return  string
     */
    public function fetchView($fileName)
    {
        $templates_path = Mage::getModuleDir('', $this->getModuleName());
        $this->setScriptPath($templates_path . '/design/adminhtml/templates');
        return parent::fetchView($this->getTemplate());
    }
}