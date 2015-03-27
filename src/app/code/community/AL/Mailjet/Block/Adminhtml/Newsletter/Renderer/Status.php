<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_Block_Adminhtml_Newsletter_Renderer_Status
 */
class AL_Mailjet_Block_Adminhtml_Newsletter_Renderer_Status
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $config = Mage::getSingleton('al_mailjet/config'); /* @var $config AL_Mailjet_Model_Config */
        $status = Mage::getModel('al_mailjet/api_contact')
            ->getStatus($row->getSubscriberEmail(), $config->getApiConfig('listid'));
        switch ($status) {
            case 1:
                $statusRenderer = Mage::helper('al_mailjet')->__('Not subscribed');
                break;
            case 0:
                $statusRenderer = Mage::helper('al_mailjet')->__('Subscribed');
                break;
            default:
                $statusRenderer = Mage::helper('al_mailjet')->__('Unknown');
                break;
        }

        return $statusRenderer;
    }
}