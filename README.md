# SMSGlobal PHP SDK

[![Latest Version](https://img.shields.io/github/release/smsglobal/smsglobal-php.svg?style=flat)](https://github.com/smsglobal/smsglobal-php/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/smsglobal/smsglobal-php.svg?style=flat)](https://packagist.org/packages/smsglobal/smsglobal-php)
![Build](https://github.com/smsglobal/smsglobal-php/workflows/Build/badge.svg)

### SMSGlobal REST API and Libraries for PHP

This is an SDK for the SMSGlobal REST API. Get an API key from SMSGlobal by signing up and viewing the API key page in the MXT platform. Learn more [www.smsglobal.com](https://www.smsglobal.com/)

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

View the [REST API](https://www.smsglobal.com/rest-api/) documentation for a list of available resources.

For more queries contact [www.smsglobal.com](https://www.smsglobal.com/contact/) 
