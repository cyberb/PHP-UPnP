PHP IGD/UPnP Library

Implemented IGD v1 Methods

1. GetExternalIPAddress
2. GetGenericPortMappingEntry
3. AddPortMapping
4. DeletePortMapping

Usage

```php
$upnp = new Igd(new UPnP());
$upnp->discover();
$externalAddress = $upnp->getExternalAddress();
```

Check integration tests for different use cases
https://github.com/cyberb/PHP-UPnP/blob/master/src/test/integration/igd.php

[![Build Status](https://secure.travis-ci.org/cyberb/PHP-UPnP.png?branch=master)](http://travis-ci.org/cyberb/PHP-UPnP)
