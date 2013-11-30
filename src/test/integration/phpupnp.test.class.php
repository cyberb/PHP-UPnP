<?php

class UPnP_Integration_Test extends \PHPUnit_Framework_TestCase {

    public function test_Discover_IGD_Url() {
        $upnp = new phpUPnP();
        $upnp->discoverIGDUrl();
        $url = $upnp->getDefaultURL();

        $this->assertEquals(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED), $url);
    }

    public function test_IGD_External_Address() {
        $upnp = new phpUPnP();
        $upnp->discoverIGDUrl();
        $externalAddress = $upnp->getExternalAddress();

        $this->assertEquals(filter_var($externalAddress, FILTER_VALIDATE_IP), $externalAddress);
    }
}