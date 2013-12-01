<?php

class Igd {

    private $upnp = null;
    private $controlUrl = null;

    const GET_EXTERNAL_IP = "GetExternalIPAddress";

    const TYPE = "WANPPPConnection:1";

    public function __construct(UPnP $upnp) {
        $this->upnp = $upnp;
    }

    public function discover() {
        $this->controlUrl = $this->upnp->discover("urn:schemas-upnp-org:device:InternetGatewayDevice:1");
    }

    public function getExternalAddress()
    {

        $this->checkDiscovery();

        $controlXml = file_get_contents($this->controlUrl);

        $url = IgdParser::parseAddressUrl($controlXml);

        $purl = parse_url($url);

        $xml = $this->upnp->call(self::GET_EXTERNAL_IP, array(), $url, self::TYPE, $purl['host'], $purl['port']);

        return IgdParser::parseAddress($xml);
    }

    public function getURL()
    {
        return $this->controlUrl;
    }

    public function checkDiscovery()
    {
        if (is_null($this->controlUrl))
            throw new Exception('not discovered yet, call discover first');
    }
}