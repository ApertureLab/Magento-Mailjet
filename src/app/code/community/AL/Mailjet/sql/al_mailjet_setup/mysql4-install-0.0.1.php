<?php
/**
 * @package    AL_Mailjet
 * @copyright  Copyright (c) 2015 Arnaud Ligny
 * @author     Arnaud Ligny <arnaud@ligny.org>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$installer->getTable('newsletter_subscriber')}
    ADD (
        `mailjet_contactid` TEXT NULL,
        `mailjet_listid` TEXT NULL
    )
");
 
$installer->endSetup();