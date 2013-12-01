<?php
class UPnpRequest {

    private $body;
    private $header;

    public function __construct($method, $arguments, $type, $host, $port) {
        $body  ='<?xml version="1.0" encoding="utf-8"?>' . "\r\n";
        $body .='<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">' . "\r\n";
        $body .='   <s:Body>' . "\r\n";
        $body .='      <u:'.$method.' xmlns:u="urn:schemas-upnp-org:service:'.$type.'">' . "\r\n";

        foreach( $arguments as $arg=>$value ) {
            $body .='         <'.$arg.'>'.$value.'</'.$arg.'>' . "\r\n";
        }

        $body .='      </u:'.$method.'>' . "\r\n";
        $body .='   </s:Body>' . "\r\n";
        $body .='</s:Envelope>' . "\r\n\r\n";

        $this->body = $body;

        $this->header = array(
            'SOAPACTION: "urn:schemas-upnp-org:service:'.$type.'#'.$method,
            'CONTENT-TYPE: text/xml ; charset="utf-8"',
            'HOST: '.$host.':'.$port,
            'Connection: close',
            'Content-Length: ' . strlen($body),
        );
    }

    public function headers()
    {
        return $this->header;
    }

    public function body()
    {
        return $this->body;
    }
}