<?php

class UPnP
{

	public function search( $st = 'ssdp:all', $mx = 2, $man = 'ssdp:discover', $host = '239.255.255.250', $port = 1900, $sockTimout = '5' )
	{
        $msg = MSearchParser::request($host, $port, $man, $mx, $st);

		$sock = socket_create( AF_INET, SOCK_DGRAM, 0 );
		socket_set_option( $sock, 1, 6, TRUE );
		socket_sendto( $sock, $msg, strlen( $msg ), 0, $host, $port);

		socket_set_option( $sock, SOL_SOCKET, SO_RCVTIMEO, array( 'sec'=>$sockTimout, 'usec'=>'0' ) );

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
        $response = $this->search($device);
        $location = 'location';
        if (count($response) > 0 && array_key_exists($location, $response[0])) {
            return $response[0][$location];
        } else {
            throw new Exception("Unable to find any $device");
        }
    }

	public function call( $method, $arguments, $url, $type)
	{

        $purl = parse_url($url);
        $request = new UPnpRequest($method, $arguments, $type, $purl['host'], $purl['port']);

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $request->headers() );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $request->body());

		$response = curl_exec( $ch );
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close( $ch );

        if ($code == 500) {
            throw new Exception($response);
        }

		return $response;
	}

}