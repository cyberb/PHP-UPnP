<?php
class IgdParser {

    public static function parseAddress($xml)
    {
        $addressXpath = self::getDom($xml);
        return self::extract($addressXpath, 'NewExternalIPAddress');
    }

    public static function parsePortMappingEntry($xml)
    {
        $addressXpath = self::getDom($xml);
        $entry = new PortMappingEntry();
        $entry->remoteHost = self::extract($addressXpath, 'NewRemoteHost');
        $entry->externalPort = self::extract($addressXpath, 'NewExternalPort');
        $entry->protocol = self::extract($addressXpath, 'NewProtocol');
        $entry->internalPort = self::extract($addressXpath, 'NewInternalPort');
        $entry->internalClient = self::extract($addressXpath, 'NewInternalClient');
        $entry->enabled = self::extract($addressXpath, 'NewEnabled');
        $entry->portMappingDescription = self::extract($addressXpath, 'NewPortMappingDescription');
        $entry->leaseDuration = self::extract($addressXpath, 'NewLeaseDuration');
        return $entry;
    }

    public static function parseControlUrl($xml)
    {
        $xpath = self::getDom($xml);
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

    public static function extract(DOMXpath $addressXpath, $param)
    {
        $result = $addressXpath->query("//*[local-name()='".$param."']");
        if ($result->length > 0)
            return $result->item(0)->nodeValue;
        else
            return null;
    }

    public static function getDom($xml)
    {
        $addressDoc = new DOMDocument();
        $addressDoc->loadXML($xml);
        $addressXpath = new DOMXpath($addressDoc);
        return $addressXpath;
    }
}