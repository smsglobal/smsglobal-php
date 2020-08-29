<?php

require_once __DIR__ . '/vendor/autoload.php';

// get your REST API keys from MXT https://mxt.smsglobal.com/integrations
\SMSGlobal\Credentials::set('YOUR_API_KEY', 'YOUR_SECRET_KEY');

$sms = new \SMSGlobal\Resource\Sms();

try {
    $response = $sms->sendToOne('DESTINATION_NUMBER', 'This is a test message.');
    print_r($response['messages'][0]);
} catch (\Exception $e) {
    echo $e->getMessage();
}
