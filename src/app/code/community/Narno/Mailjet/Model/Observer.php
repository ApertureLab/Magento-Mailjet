<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2010-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Narno Mailjet Observer
 *
 * Event handlers
 */
class Narno_Mailjet_Model_Observer
{
    /**
     * Adds column(s) to Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
     * 
     * @param Varien_Event_Observer $observer
     */
    public function beforeBlockToHtml(Varien_Event_Observer $observer)
    {
        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if ($config->isApiEnabled() === false) {
            return $this;
        }

        $grid = $observer->getBlock();

        /**
         * Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
         */
        if ($grid instanceof Mage_Adminhtml_Block_Newsletter_Subscriber_Grid) {
            // Mailjet status
            $grid->addColumnAfter('mailjet_status', array(
                'header'    => Mage::helper('newsletter')->__('Mailjet status' . $grid->getLastColumnId()),
                'index'     => 'mailjet_status',
                'width'     => '95px',
                'renderer'  => new Narno_Mailjet_Block_Adminhtml_Newsletter_Renderer_Status(),
                'filter'    => false,
                'sortable'  => false,
            ), 'store');
        }

        return $this;
    }

    /**
     * Observes "customer_save_after" event
     * to add customer to Mailjet through the API
     *
     * @param Varien_Event_Observer $observer
     * @return Narno_Mailjet_Model_Observer
     */
    public function updateCustomer(Varien_Event_Observer $observer)
    {
        Mage::helper('narno_mailjet')->logDebug('Observer: ' . $observer->getEvent()->getName()); // debug
        
        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if ($config->isApiEnabled() === false) {
            return $this;
        }

        $customer = $observer->getEvent()->getCustomer();
        $mjLists = Mage::getModel('narno_mailjet/api_lists');
        /* @var $mjLists Narno_Mailjet_Model_Api_Lists */

        // subscribe
        if (Mage::getModel('newsletter/subscriber')->loadByCustomer($customer)->isSubscribed()) {
            $mjLists->subscribe($customer->getEmail(), $config->getApiConfig('listid'));
        }
        // unsubscribe
        else {
            $mjLists->unsubscribe($customer->getEmail(), $config->getApiConfig('listid'));
        }

        return $this;
    }

    /**
     * Observes "customer_delete_before" event
     * to unsubscribe customer to Mailjet through the API
     *
     * @param Varien_Event_Observer $observer
     * @return Narno_Mailjet_Model_Observer
     */
    public function deleteCustomer(Varien_Event_Observer $observer)
    {
        Mage::helper('narno_mailjet')->logDebug('Observer: ' . $observer->getEvent()->getName()); // debug

        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if ($config->isApiEnabled() === false) {
            return $this;
        }

        $customer   = $observer->getEvent()->getCustomer();
        $mjLists = Mage::getModel('narno_mailjet/api_lists');
        /* @var $mjLists Narno_Mailjet_Model_Api_Lists */
        
        // unsubscribe
        $mjLists->unsubscribe($customer->getEmail(), $config->getApiConfig('listid'));

        return $this;
    }

    /**
     * Observes "newsletter_subscriber_save_before" event
     * to update subscriber change status date
     *
     * @param Varien_Event_Observer $observer
     * @return Narno_Mailjet_Model_Observer
     */
    public function updateSubscriberStatusDate(Varien_Event_Observer $observer)
    {
        Mage::helper('narno_mailjet')->logDebug('Observer: ' . $observer->getEvent()->getName()); // debug

        $subscriber = $observer->getEvent()->getSubscriber();
        // date (Zend_Date with default time zone)
        $date = Mage::app()->getLocale()->date(null, null, null, false);
        // set change status date
        $subscriber->setChangeStatusAt($date->toString('yyyy-MM-dd HH:mm:ss'));

        Mage::helper('narno_mailjet')->logDebug('Update date: ' . $date->toString('yyyy-MM-dd HH:mm:ss')); // debug

        return $this;
    }

    /**
     * Observes "newsletter_subscriber_save_after" event
     * to send subscriber to Mailjet through the API
     *
     * @param Varien_Event_Observer $observer
     * @return Narno_Mailjet_Model_Observer
     */
    public function updateSubscriber(Varien_Event_Observer $observer)
    {
        Mage::helper('narno_mailjet')->logDebug('Observer: ' . $observer->getEvent()->getName()); // debug
        
        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if ($config->isApiEnabled() === false) {
            return $this;
        }

        $subscriber = $observer->getEvent()->getSubscriber();
        $mjLists = Mage::getModel('narno_mailjet/api_lists');
        /* @var $mjLists Narno_Mailjet_Model_Api_Lists */

        // guest
        if (!$subscriber->getCustomerId()) {
            // subscribe
            if ($subscriber->getSubscriberStatus() == '1') {
                $mjLists->subscribe($subscriber->getSubscriberEmail(), $config->getApiConfig('listid'));
            }
            // unsubscribe
            else {
                $mjLists->unsubscribe($subscriber->getSubscriberEmail(), $config->getApiConfig('listid'));
            }
        }
        // customer
        else {
            if ($subscriber->getSubscriberStatus() != '1') {
                $mjLists->unsubscribe($subscriber->getEmail(), $config->getApiConfig('listid'));
            }
        }

        return $this;
    }
    
    /**
     * Observes "newsletter_subscriber_delete_after" event
     * to unsubscribe subscriber to Mailjet through the API
     * 
     * @param Varien_Event_Observer $observer
     * @return Narno_Mailjet_Model_Observer
     */
    public function deleteSubscriber(Varien_Event_Observer $observer)
    {
        Mage::helper('narno_mailjet')->logDebug('Observer: ' . $observer->getEvent()->getName()); // debug

        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if ($config->isApiEnabled() === false) {
            return $this;
        }

        $subscriber = $observer->getEvent()->getSubscriber();
        $mjLists = Mage::getModel('narno_mailjet/api_lists');
        /* @var $mjLists Narno_Mailjet_Model_Api_Lists */

        // guest
        if (!$subscriber->getCustomerId()) {
            $mjLists->unsubscribe($subscriber->getSubscriberEmail(), $config->getApiConfig('listid'));
        }
        // customer
        else {
            $mjLists->unsubscribe($subscriber->getEmail(), $config->getApiConfig('listid'));
        }

        return $this;
    }  

    /**
     * Scheduled actions (cron)
     * Asynchronous process
     *
     * @param Mage_Cron_Model_Schedule $schedule
     * @return Narno_Mailjet_Model_Observer
     */
    public function scheduledActions($schedule)
    {
        $config = Mage::getSingleton('narno_mailjet/config'); /* @var $config Narno_Mailjet_Model_Config */

        if ($config->isApiEnabled() === false) {
            return $this;
        }
        
        $date = Mage::app()->getLocale()->date(null, null, null, false);
        Mage::log('run at ' . $date->toString('yyyy-MM-dd HH:mm:ss'), Zend_Log::INFO, 'cron.mailjet.log');

        return $this;
    }
}