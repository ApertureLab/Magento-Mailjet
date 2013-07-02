<?php
/**
 * @category   Narno
 * @package    Narno_Mailjet
 * @copyright  Copyright (c) 2010-2013 Narno (http://narno.com)
 * @author     Arnaud Ligny <contact@narno.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Narno Mailjet custom XML log formatter
 */
class Narno_Mailjet_Model_Logger_FormatterXml extends Zend_Log_Formatter_Xml
{
    public function format($event)
    {
        if ($this->_elementMap === null) {
            $dataToInsert = $event;
        } else {
            $dataToInsert = array();
            foreach ($this->_elementMap as $elementName => $fieldKey) {
                $dataToInsert[$elementName] = $event[$fieldKey];
            }
        }
        $dom = new DOMDocument();
        $elt = $dom->appendChild(new DOMElement($this->_rootElement));
        foreach ($dataToInsert as $key => $value) {
            // protect error message
            if ($key == 'error-description') {
                $descElt = new DOMElement($key, '');
                $elt->appendChild($descElt);
                $descElt->appendChild($dom->createCDATASection(str_replace("\n", '', $value)));
            }
            else {
                $elt->appendChild(new DOMElement($key, $value));
            }
        }
        $xml = $dom->saveXML();
        $xml = $this->_cleanXml($xml);
        return $xml;
    }
    
    private function _cleanXml($xml) {
        return preg_replace('/<\?xml version="1.0"( encoding="[^\"]*")?\?>\n/u', '', $xml);
    }
}