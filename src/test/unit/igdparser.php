<?php

class IgdParser_Test extends \PHPUnit_Framework_TestCase {
    public function test_ParseAddressUrl() {
        $xml = file_get_contents(__DIR__ ."/control.response.xml");
        $this->assertEquals(
            'http://192.168.1.254:2555/upnp/UPnP_router_guid_ppp0/WANPPPConn1.ctl',
            IgdParser::parseAddressUrl($xml));
    }

    public function test_ParseAddress() {
        $xml = file_get_contents(__DIR__ ."/address.response.xml");
        $this->assertEquals('111.111.111.111', IgdParser::parseAddress($xml));
    }
}
