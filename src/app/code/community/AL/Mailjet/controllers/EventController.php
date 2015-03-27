<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class AL_Mailjet_EventController
 */
class AL_Mailjet_EventController
    extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $websiteId = Mage::app()->getStore()->getWebsiteId();
            $subscriber = Mage::getModel('newsletter/subscriber')
                ->setWebsiteId($websiteId);
            $data = json_decode($this->getRequest()->getRawBody());

            // @todo manage unsubscribe event
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
                '<h1>Mailjet module event trigger API endpoint</h1>
                <p>Method not allowed</p>'
            );
        }
    }
}