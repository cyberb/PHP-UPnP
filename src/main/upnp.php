<?php

/**
 * Communicate with UPnP devices.
 *
 * Not static for being able to have instances for different devices.
 *
 * @author Morten Hekkvang <artheus@github>
 *
 * @todo Create config file.
 * @todo Better commenting.
 * @todo Add security checks for eg. arguments.
 * @todo Response parsing before return in SOAP-method calls.
 */
class UPnP
{

	private $curlHandle = null;

	/**
	 * Perform an M-SEARCH multicast request for detecting UPnP-devices in network.
	 *
	 * @todo Allow unicasting.
	 * @todo Sort arguments better.
	 */
	public function mSearch( $st = 'ssdp:all', $mx = 2, $man = 'ssdp:discover', $host = '239.255.255.250', $port = 1900, $sockTimout = '5' )
	{
        $msg = MSearchParser::request($host, $port, $man, $mx, $st);

		// MULTICAST MESSAGE
		$sock = socket_create( AF_INET, SOCK_DGRAM, 0 );
		$opt_ret = socket_set_option( $sock, 1, 6, TRUE );
		$send_ret = socket_sendto( $sock, $msg, strlen( $msg ), 0, $host, $port);

		// SET TIMEOUT FOR RECIEVE
		socket_set_option( $sock, SOL_SOCKET, SO_RCVTIMEO, array( 'sec'=>$sockTimout, 'usec'=>'0' ) );

		// RECIEVE RESPONSE
		$response = array();
		do {
			$buf = null;
            $name = null;
            $rcv_port = null;
            @socket_recvfrom( $sock, $buf, 1024, MSG_WAITALL, $name , $rcv_port);
			if( !is_null($buf) )$response[] = MSearchParser::parseResponse( $buf );
		} while( !is_null($buf) );

		socket_close( $sock );

		return $response;
	}

    public function discover($device) {
        $response = $this->mSearch($device);
        $location = 'location';
        if (count($response) > 0 && array_key_exists($location, $response[0])) {
            return $response[0][$location];
        } else {
            throw new Exception("Unable to find any $device");
        }
    }



	/**
	 * Get the curl handle for performing soap requests.
	 */
	public function getCurlHandle()
	{
		if( is_null( $this->curlHandle ) ) {
			$this->curlHandle = curl_init();
		}

		return $this->curlHandle;
	}

	public function sendRequestToDevice( $method, $arguments, $url = null, $type = 'RenderingControl:1', $host = '127.0.0.1', $port = '80' )
	{

        $request = new UPnpRequest($method, $arguments, $type, $host, $port);

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $request->headers() );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $request->body());

		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}

}