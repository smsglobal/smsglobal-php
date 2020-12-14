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

**Send OTP**

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$otp = new \SMSGlobal\Resource\Otp();

try {
    $response = $otp->send('DESTINATION_NUMBER', '{*code*} is your SMSGlobal verification code.');
    print_r($response);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

The following json response will be returned by the server:

```json

{
    "requestId": "404372541683674336263499",
    "validUnitlTimestamp": "2020-11-18 16:24:51",
    "createdTimestamp": "2020-11-18 16:22:51",
    "lastEventTimestamp": "2020-11-18 16:22:51",
    "destination": "61400000000",
    "status": "Sent"
}

```


**Verify OTP**

The OTP code entered by your user can be verified by either using `requestId` or `destination number`. The followings are examples of each method:

```php

<?php


require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$otp = new \SMSGlobal\Resource\Otp();

try {
    $response = $otp->verifyByRequestId('request Id', 'OTP code enterted by your user.');
    print_r($response);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$otp = new \SMSGlobal\Resource\Otp();

try {
    $response = $otp->verifyByDestination('destination number', 'OTP code enterted by your user.');
    print_r($response);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

The following json response will be returned by the server if verification is successfull:

```json

{
    "requestId": "404372541683674336263499",
    "validUnitlTimestamp": "2020-11-18 16:24:51",
    "createdTimestamp": "2020-11-18 16:22:51",
    "lastEventTimestamp": "2020-11-18 16:22:51",
    "destination": "61400000000",
    "status": "Verified"
}

```

**Cancel OTP**

The OTP request can be cancelled if an OTP is not expired and verified yet. It can be done by either using `requestId` or `destination number`. The followings are examples of each method:

```php

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$otp = new \SMSGlobal\Resource\Otp();

try {
    $response = $otp->cancelByRequestId('request Id');
    print_r($response);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```


```php

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$otp = new \SMSGlobal\Resource\Otp();

try {
    $response = $otp->cancelByDestination('destination number');
    print_r($response);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```


The following json response will be returned by the server if cancellation is successfull:

```json

{
    "requestId": "404372541683674336263499",
    "validUnitlTimestamp": "2020-11-18 16:24:51",
    "createdTimestamp": "2020-11-18 16:22:51",
    "lastEventTimestamp": "2020-11-18 16:22:51",
    "destination": "61400000000",
    "status": "Cancelled"
}

```


### Available REST API Resources  

* Sms
* Sms Incoming
* OTP (beta)
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

For any query [contact us](https://www.smsglobal.com/contact/?utm_source=dev&utm_medium=github&utm_campaign=php_sdk)
