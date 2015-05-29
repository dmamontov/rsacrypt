[![Latest Stable Version](https://poser.pugx.org/dmamontov/rsacrypt/v/stable.svg)](https://packagist.org/packages/dmamontov/rsacrypt)
[![License](https://poser.pugx.org/dmamontov/rsacrypt/license.svg)](https://packagist.org/packages/dmamontov/rsacrypt)

RSA Crypt
=========

It generates a public and private key and encrypts and decrypts the data with the help they are even.


## Requirements
* PHP version ~5.3.0

## Installation

1) Install [composer](https://getcomposer.org/download/)

2) Follow in the project folder:
```bash
composer require dmamontov/rsacrypt ~1.0.0
```

In config `composer.json` your project will be added to the library `dmamontov/rsacrypt`, who settled in the folder `vendor/`. In the absence of a config file or folder with vendors they will be created.

If before your project is not used `composer`, connect the startup file vendors. To do this, enter the code in the project:
```php
require 'path/to/vendor/autoload.php';
```

### Example of work
```php
$crypt = new RsaCrypt;

$crypt->genKeys(2048);
$crypt->setPublicKey('public.pem');
$crypt->setPrivateKey('private.pem');
$data = $crypt->encrypt("Test Crypt");

echo $data;
echo $crypt->decrypt($data);
```
