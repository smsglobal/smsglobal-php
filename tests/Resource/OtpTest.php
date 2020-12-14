<?php

namespace SMSGlobal\Tests\Resource;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SMSGlobal\Credentials;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\CredentialsException;
use SMSGlobal\Exceptions\InvalidPayloadException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;
use SMSGlobal\Resource\Otp;
use SMSGlobal\Resource\Sms;

class OtpTest extends TestCase
{

    protected $credentials;

    protected $apiKey = 'key12345';
    protected $apiSecret = 'secret12345';

    public function setUp(): void
    {
        $this->credentials = Credentials::set($this->apiKey, $this->apiSecret);
    }

    public function testOtpInstance(): void
    {
        $this->assertInstanceOf(Otp::class, new Otp());
    }

    public function testCancelByRequestIdNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        try {
            $otp = new Otp($client);
            $otp->cancelByRequestId('404372541682577504482079');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testCancelByRequestId(): void
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Cancelled"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->cancelByRequestId('404372541682577504482079');
            $this->assertEquals('404372541682577504482079', $otpResponse['requestId']);
            $this->assertEquals('Cancelled', $otpResponse['status']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testCancelByDestinationNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        try {
            $otp = new Otp($client);
            $otp->cancelByDestination('404372541682577504482079');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testCancelByDestination(): void
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Cancelled"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->cancelByDestination('61400000000');
            $this->assertEquals('61400000000', $otpResponse['destination']);
            $this->assertEquals('Cancelled', $otpResponse['status']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testSend(): void
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Sent"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->send('destination', '{*code*} is your SMSGlobal verification code.');
            $this->assertEquals('404372541682577504482079', $otpResponse['requestId']);
            $this->assertEquals('61400000000', $otpResponse['destination']);
            $this->assertEquals('Sent', $otpResponse['status']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidPayloadException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testSendWithAllArguments()
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Sent"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $messageExpiry = new \DateTime('now', new \DateTimeZone('UTC'));
        $messageExpiry->add(new \DateInterval('PT1H'));

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->send('destination', '{*code*} is your SMSGlobal verification code.', 'SMSGlobal', 300, 4, $messageExpiry);
            $this->assertEquals('404372541682577504482079', $otpResponse['requestId']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidPayloadException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testSendRawPayload(): void
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Sent"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->rawPayload([
                'destination' => 'destination',
                'message' => 'This is a test message',
            ]);
            $this->assertEquals('404372541682577504482079', $otpResponse['requestId']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidPayloadException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testSendInvalidRawPayload(): void
    {
        $this->expectException(InvalidPayloadException::class);

        try {

            $messageExpiry = new \DateTime('now', new \DateTimeZone('UTC'));
            $messageExpiry->add(new \DateInterval('PT1H'));

            $otp = new Otp();
            // failing with using non UTF8 text.
            $otp->rawPayload(['message' => utf8_decode("ü"), 'messageExpiryDateTime' => $messageExpiry]);
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testHttpClientBadRequestException()
    {
        $this->expectException(GuzzleException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(400),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            // failing with using non UTF8 text.
            $otp->rawPayload(['destination' => 'destination', 'message' => 'OTP message without placeholder']);
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidPayloadException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testVerfiyByRequestIdNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otp->verifyByRequestId('404372541682577504482079', '432423');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testVerifiedByRequestId()
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Verified"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->verifyByRequestId('404372541682577504482079', '42112');
            $this->assertEquals('404372541682577504482079', $otpResponse['requestId']);
            $this->assertEquals('Verified', $otpResponse['status']);
            $this->assertEquals('61400000000', $otpResponse['destination']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testVerifiedByRequestIdWithMalformedCode(): void
    {
        $this->expectException(InvalidPayloadException::class);

        try {
            $otp = new Otp();
            // failing with using non UTF8 text.
            $otp->verifyByRequestId('404372541682577504482079', utf8_decode("ü"));
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testVerfiyByDestinationNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otp->verifyByDestination('61400000000', '432423');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testVerifiedByDestination()
    {
        $responseBody = '{"requestId":"404372541682577504482079","destination": "61400000000", "validUnitlTimestamp":"2020-11-06 13:59:11","createdTimestamp":"2020-11-06 13:49:11","lastEventTimestamp":"2020-11-06 13:49:33","status":"Verified"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $otp = new Otp($client);
            $otpResponse = $otp->verifyByDestination('404372541682577504482079', '42112');
            $this->assertEquals('Verified', $otpResponse['status']);
            $this->assertEquals('61400000000', $otpResponse['destination']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        }
    }
}
