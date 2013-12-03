## PHP IGD/UPnP Library

#### Implemented IGD v1 Methods

1. GetExternalIPAddress
2. GetGenericPortMappingEntry
3. AddPortMapping
4. DeletePortMapping

#### Usage
```php
$upnp = new Igd(new UPnP());
$upnp->discover();
$externalAddress = $upnp->getExternalAddress();
```

[More samples](src/test/integration/igd.php)

[![Build Status](https://secure.travis-ci.org/cyberb/PHP-UPnP.png?branch=master)](http://travis-ci.org/cyberb/PHP-UPnP)
