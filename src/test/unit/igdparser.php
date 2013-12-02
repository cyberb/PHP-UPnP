<?php

class IgdParser_Test extends \PHPUnit_Framework_TestCase {
    public function test_ParseControlUrl() {
        $xml = file_get_contents(__DIR__ ."/control.response.xml");
        $this->assertEquals(
            'http://192.168.1.254:2555/upnp/UPnP_router_guid_ppp0/WANPPPConn1.ctl',
            IgdParser::parseControlUrl($xml));
    }

    public function test_ParseAddress() {
        $xml = file_get_contents(__DIR__ ."/address.response.xml");
        $this->assertEquals('111.111.111.111', IgdParser::parseAddress($xml));
    }

    public function test_ParsePortMapping() {
        $xml = file_get_contents(__DIR__ ."/portmapping.response.xml");
        $entry = IgdParser::parsePortMappingEntry($xml);
        $this->assertEquals('', $entry->remoteHost);
        $this->assertEquals(21, $entry->externalPort);
        $this->assertEquals('TCP', $entry->protocol);
        $this->assertEquals(0, $entry->internalPort);
        $this->assertEquals('', $entry->internalClient);
        $this->assertEquals(0, $entry->enabled);
        $this->assertEquals('FTP', $entry->portMappingDescription);
        $this->assertEquals(0, $entry->leaseDuration);
    }
}
