<?php

/**
 * Not tested AV prototype
 */
class Av {
    private $upnp = null;
    private $url = null;

    public function __construct(UPnP $upnp) {
        $this->upnp = $upnp;
    }

    public function discover() {
        $this->url = $this->upnp->discover('ssdp:all');
    }

    public function setVolume( $desiredVolume = 0, $channel = 'Master', $instanceId = 0 )
    {
        return $this->upnp->call( 'SetVolume', array(
            'DesiredVolume' => $desiredVolume,
            'Channel' => $channel,
            'InstanceId' => $instanceId,
        ));
    }

    public function setMute( $desiredMute = 1, $channel = 'Master', $instanceId = 0 )
    {
        return $this->upnp->call( 'SetMute', array(
            'DesiredMute' => $desiredMute,
            'Channel' => $channel,
            'InstanceId' => $instanceId,
        ));
    }

}