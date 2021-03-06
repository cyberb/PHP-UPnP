<?php

class Igd
{

    private $upnp = null;
    private $controlUrl = null;

    const GetExternalIPAddress = "GetExternalIPAddress";
    const GetGenericPortMappingEntry = "GetGenericPortMappingEntry";
    const AddPortMapping = "AddPortMapping";
    const DeletePortMapping = "DeletePortMapping";

    const WANPPPConnection = "WANPPPConnection:1";

    const InternetGatewayDevice = "urn:schemas-upnp-org:device:InternetGatewayDevice:1";

    const LIMIT = 100000;

    public function __construct(UPnP $upnp)
    {
        $this->upnp = $upnp;
    }

    public function discover()
    {
        $this->controlUrl = $this->upnp->discover(self::InternetGatewayDevice);
    }

    public function getExternalAddress()
    {
        $url = $this->getControlUrl();
        $xml = $this->upnp->call(self::GetExternalIPAddress, array(), $url, self::WANPPPConnection);
        return IgdParser::parseAddress($xml);
    }

    public function addPortMapping($localIp, $localPort, $externalPort, $protocol, $description = 'php igd/upnp library', $duration = 0)
    {
        $url = $this->getControlUrl();

        $this->upnp->call(
            self::AddPortMapping,
            array(
                'NewExternalPort' => $externalPort,
                'NewProtocol' => $protocol,
                'NewInternalPort' => $localPort,
                'NewInternalClient' => $localIp,
                'NewEnabled' => 1,
                'NewPortMappingDescription' => $description,
                'NewLeaseDuration' => $duration
            ),
            $url,
            self::WANPPPConnection);

    }

    public function deletePortMapping($externalPort, $protocol)
    {
        $url = $this->getControlUrl();

        $this->upnp->call(
            self::DeletePortMapping,
            array(
                'NewExternalPort' => $externalPort,
                'NewProtocol' => $protocol
            ),
            $url,
            self::WANPPPConnection);

    }

    public function getPortMappings()
    {
        $url = $this->getControlUrl();

        $resuls = array();

        for ($i = 0; $i < self::LIMIT; $i++) {

            try {
            $xml = $this->upnp->call(
                self::GetGenericPortMappingEntry,
                array('NewPortMappingIndex' => $i),
                $url,
                self::WANPPPConnection);

            } catch (Exception $e) {
                break;
            }
            $resuls[] = IgdParser::parsePortMappingEntry($xml);
        }

        return $resuls;

    }

    public function findPortMapping($localIp, $localPort, $externalPort, $protocol)
    {
        $mappings = $this->getPortMappings();
        $filtered_array = array_filter($mappings, function ($item) use ($localIp, $localPort, $externalPort, $protocol) {
            /* @var $item PortMappingEntry */
            return (
                $item->externalPort == $externalPort &&
                $item->internalClient == $localIp &&
                $item->internalPort == $localPort &&
                $item->protocol == $protocol
            );
        });
        return $filtered_array;
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

   public function getControlUrl()
    {
        $this->checkDiscovery();

        $controlXml = file_get_contents($this->controlUrl);

        $url = IgdParser::parseControlUrl($controlXml);
        return $url;
    }
}