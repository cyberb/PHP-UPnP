<?php
class MSearchParser {

    const USER_AGENT = 'UPnP/1.1 PHP-UPnP/0.0.1a';

    public static function request($host, $port, $man, $mx, $st) {
        $msg  = 'M-SEARCH * HTTP/1.1' . "\r\n";
        $msg .= 'HOST: '.$host.':'.$port."\r\n";
        $msg .= 'MAN: "'. $man .'"' . "\r\n";
        $msg .= 'MX: '. $mx ."\r\n";
        $msg .= 'ST:' . $st ."\r\n";
        $msg .= 'USER-AGENT: '. static::USER_AGENT ."\r\n";
        $msg .= '' ."\r\n";

        return $msg;
    }

    public static function parseResponse( $response )
    {
        $responseArr = explode( "\r\n", $response );

        $parsedResponse = array();

        foreach( $responseArr as $row ) {
            if( stripos( $row, 'http' ) === 0 )
                $parsedResponse['http'] = $row;

            if( stripos( $row, 'cach' ) === 0 )
                $parsedResponse['cache-control'] = str_ireplace( 'cache-control: ', '', $row );

            if( stripos( $row, 'date') === 0 )
                $parsedResponse['date'] = str_ireplace( 'date: ', '', $row );

            if( stripos( $row, 'ext') === 0 )
                $parsedResponse['ext'] = str_ireplace( 'ext: ', '', $row );

            if( stripos( $row, 'loca') === 0 )
                $parsedResponse['location'] = str_ireplace( 'location: ', '', $row );

            if( stripos( $row, 'serv') === 0 )
                $parsedResponse['server'] = str_ireplace( 'server: ', '', $row );

            if( stripos( $row, 'st:') === 0 )
                $parsedResponse['st'] = str_ireplace( 'st: ', '', $row );

            if( stripos( $row, 'usn:') === 0 )
                $parsedResponse['usn'] = str_ireplace( 'usn: ', '', $row );

            if( stripos( $row, 'cont') === 0 )
                $parsedResponse['content-length'] = str_ireplace( 'content-length: ', '', $row );
        }

        return $parsedResponse;
    }
}