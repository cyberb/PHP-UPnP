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

    public function test_Get_Port_Mappings() {
        $upnp = new Igd(new UPnP());
        $upnp->discover();
        $mappings = $upnp->getPortMappings();

        echo(sizeof($mappings));

        $this->assertGreaterThan(0, sizeof($mappings));
    }

    public function test_Add_Remove_Port_Mapping() {
        $upnp = new Igd(new UPnP());
        $upnp->discover();

        $localIp = exec('hostname -I');
        $localPort = 10011;
        $externalPort = 10013;
        $protocol = 'TCP';

        echo("checking for existing mapping\n");
        $found = $upnp->findPortMapping($localIp, $localPort, $externalPort, $protocol);

        if (sizeof($found) > 1) {
            var_dump($found);
            throw new Exception("found more than one mapping, should not happen, check yur router state");
        }

        if (sizeof($found) > 0) {
            echo("removing existing mapping\n");
            $upnp->deletePortMapping($externalPort, $protocol);
        }

        $found = $upnp->findPortMapping($localIp, $localPort, $externalPort, $protocol);

        if (sizeof($found) > 0) {
            var_dump($found);
            throw new Exception("unable to delete\n");
        }

        echo("adding test mapping\n");
        $upnp->addPortMapping($localIp, $localPort, $externalPort, $protocol);

        $found = $upnp->findPortMapping($localIp, $localPort, $externalPort, $protocol);
        if (sizeof($found) != 1) {
            var_dump($found);
            throw new Exception("unable to add mapping\n");
        }

        echo("removing test mapping\n");
        $upnp->deletePortMapping($externalPort, $protocol);
        $found = $upnp->findPortMapping($localIp, $localPort, $externalPort, $protocol);
        if (sizeof($found) != 0) {
            var_dump($found);
            throw new Exception("unable to cleanup\n");
        }

    }

}