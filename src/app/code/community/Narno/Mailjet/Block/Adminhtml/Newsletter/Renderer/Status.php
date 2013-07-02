<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2010-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Narno Mailjet Adminhtml Block status render
 */
class Narno_Mailjet_Block_Adminhtml_Newsletter_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $status = Mage::getModel('narno_mailjet/api_lists')->getStatusByEmail($row->getSubscriberEmail());
        switch ((string)$status) {
            case Narno_Mailjet_Model_Api_Lists::STATUS_NOTEXIST:
                $statusRenderer = Mage::helper('narno_mailjet')->__('Not exist');
                break;
            case Narno_Mailjet_Model_Api_Lists::STATUS_NOTSUBSCRIBED:
                $statusRenderer = Mage::helper('narno_mailjet')->__('Not subscribed');
                break;
            case Narno_Mailjet_Model_Api_Lists::STATUS_SUBSCRIBED:
                $statusRenderer = Mage::helper('narno_mailjet')->__('Subscribed');
                break;
            default:
                $statusRenderer = Mage::helper('narno_mailjet')->__('Unknown');
                break;
        }
        
        return $statusRenderer;
    }
}