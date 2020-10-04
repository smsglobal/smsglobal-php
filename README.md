# SMSGlobal PHP Client Library

![Build](https://github.com/smsglobal/smsglobal-php/workflows/Build/badge.svg)
![Coverage](https://img.shields.io/codecov/c/gh/smsglobal/smsglobal-php)
[![Latest Version](https://img.shields.io/github/release/smsglobal/smsglobal-php.svg?style=flat)](https://github.com/smsglobal/smsglobal-php/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/smsglobal/smsglobal-php.svg?style=flat)](https://packagist.org/packages/smsglobal/smsglobal-php)

### SMSGlobal REST API and Libraries for PHP

This is a PHP Client library for SMSGlobal’s REST API to integrate SMS capabilities into your PHP application.

Sign up for a [free SMSGlobal account](https://www.smsglobal.com/mxt-sign-up/?utm_source=dev&utm_medium=github&utm_campaign=php_sdk) today and get your API Key from our advanced SMS platform, MXT. Plus, enjoy unlimited free developer sandbox testing to try out your API in full!

### Requirements

* PHP 7.2 and above
* Guzzle6 (PHP HTTP client)

### Installation

To install the PHP client library to your project, we recommend using [Composer](https://getcomposer.org/).

```bash
composer require smsglobal/smsglobal-php
```

### Usage

Check out `examples` folder

**Send SMS**

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$sms = new \SMSGlobal\Resource\Sms();

try {
    $response = $sms->sendToOne('DESTINATION_NUMBER', 'This is a test message.');
    print_r($response['messages']);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

### Available REST API Resources  

* Sms
* Sms Incoming
* User

### Unit Tests
Install development dependencies

```bash
composer require smsglobal/smsglobal-php
```

Run unit tests

```bash
./vendor/bin/phpunit tests
```

With coverage (requires extension pcov or xdebug)
```bash
./vendor/bin/phpunit --coverage-text tests
```

### Getting help

View the [REST API](https://www.smsglobal.com/rest-api/?utm_source=dev&utm_medium=github&utm_campaign=php_sdk) documentation for a list of available resources.

For more queries contact [www.smsglobal.com](https://www.smsglobal.com/contact/?utm_source=dev&utm_medium=github&utm_campaign=php_sdk) 
