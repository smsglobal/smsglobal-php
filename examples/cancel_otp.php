<?php

use SMSGlobal\Credentials;
use SMSGlobal\Resource\Otp;

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$otp = new Otp();

try {
    $response = $otp->cancelByDestination('destination number');
    print_r($response);
} catch (\Throwable $e) {
    echo $e->getMessage();
}
