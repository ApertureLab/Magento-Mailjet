<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2011-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Narno_Mailjet_EventController
    extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
        if ($this->getRequest()->isPost()) {
            $websiteId = Mage::app()->getStore()->getWebsiteId();
            $subscriber = Mage::getModel('newsletter/subscriber')
                ->setWebsiteId($websiteId);
            $data = json_decode($this->getRequest()->getRawBody());

            // manage unsubscribe event
            if ($data->event == 'unsub') {
                if ($contact = $subscriber->loadByEmail($data->email)) {
                    echo "subscriber exists";
                }
                else {
                    echo "subscriber with email $data->email does not exist";
                }
            }
            $this->getResponse()->setHttpResponseCode(200);
        }
        else {
            $this->getResponse()->setHttpResponseCode(405);
            $this->getResponse()->setBody(
                '<h1>Narno Mailjet module event trigger API endpoint</h1>
                 <p>Method not allowed</p>'
            );
        }
	}
}