<?php

class Igd {

    private $upnp = null;
    private $url = null;

    public function __construct(UPnP $upnp) {
        $this->upnp = $upnp;
    }

    public function discover() {
        $this->url = $this->upnp->discover("urn:schemas-upnp-org:device:InternetGatewayDevice:1");
    }

    public function getExternalAddress()
    {

        $this->checkDiscovery();
        $file = file_get_contents($this->url);
        $addressUrl = IgdParser::parseAddressUrl($file);

        $prsedUrl = parse_url($addressUrl);
        $addressXml = $this->upnp->sendRequestToDevice(
            "GetExternalIPAddress",
            array(),
            $addressUrl,
            "WANPPPConnection:1",
            $prsedUrl['host'],
            $prsedUrl['port']);

        return IgdParser::parseAddress($addressXml);
    }

    public function getURL()
    {
        return $this->url;
    }

    public function checkDiscovery()
    {
        if (is_null($this->url))
            throw new Exception('not discovered yet, call discover first');
    }
}