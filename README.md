[![Build Status](https://travis-ci.org/dmamontov/rsacrypt.svg?branch=master)](https://travis-ci.org/dmamontov/rsacrypt)
[![Latest Stable Version](https://poser.pugx.org/dmamontov/rsacrypt/v/stable.svg)](https://packagist.org/packages/dmamontov/rsacrypt)
[![License](https://poser.pugx.org/dmamontov/rsacrypt/license.svg)](https://packagist.org/packages/dmamontov/rsacrypt)

RSA Crypt
=========

This class can RSA generate keys and encrypt data using OpenSSL.

It can generate public and private RSA keys of given length calling the openssl program.

The class and also encrypt data with a given public key file and decrypt data with a given private key file.


## Requirements
* PHP version ~5.3.3

## Installation

1) Install [composer](https://getcomposer.org/download/)

2) Follow in the project folder:
```bash
composer require dmamontov/rsacrypt ~1.0.2
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
