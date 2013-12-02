<?php

class UPnP_Integration_Test extends \PHPUnit_Framework_TestCase {

    public function test_Discover() {
        $igd = new Igd(new UPnP());
        $igd->discover();
        $url = $igd->getURL();

        $this->assertEquals(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED), $url);
    }

    public function test_External_Address() {
        $upnp = new Igd(new UPnP());
        $upnp->discover();
        $externalAddress = $upnp->getExternalAddress();

        $this->assertEquals(filter_var($externalAddress, FILTER_VALIDATE_IP), $externalAddress);
    }

    public function test_Port_Mappings() {
        $upnp = new Igd(new UPnP());
        $upnp->discover();
        $mappings = $upnp->getPortMappings();

        echo(sizeof($mappings));

        $this->assertGreaterThan(0, sizeof($mappings));
    }
}