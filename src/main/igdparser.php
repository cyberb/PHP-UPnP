<?php
class IgdParser {

    public static function parseAddress($xml)
    {
        $addressDoc = new DOMDocument();
        $addressDoc->loadXML($xml);
        $addressXpath = new DOMXpath($addressDoc);
        return $addressXpath->query("//*[local-name()='NewExternalIPAddress']")->item(0)->nodeValue;
    }

    public static function parseAddressUrl($xml)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $xpath = new DOMXpath($doc);
        $xpath->registerNamespace('x', 'urn:schemas-upnp-org:device-1-0');
        $type = 'urn:schemas-upnp-org:service:WANPPPConnection:1';
        $urlBase = $xpath->query("//x:URLBase")->item(0)->nodeValue;
        $list = $xpath->query("//x:service[x:serviceType[.='$type']]/x:controlURL");
        if ($list->length == 1) {
            return $urlBase . $list->item(0)->nodeValue;
        } else {
            throw new Exception('unable to find address url');
        }
    }
}