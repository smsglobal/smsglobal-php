<?php

namespace SMSGlobal\Tests\Resource;

use GuzzleHttp\Client;
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
use SMSGlobal\Resource\Sms;

class SmsTest extends TestCase
{

    protected $credentials;

    protected $apiKey = 'key12345';
    protected $apiSecret = 'secret12345';

    public function setUp(): void
    {
        $this->credentials =  Credentials::set($this->apiKey, $this->apiSecret);
    }

    public function testSmsInstance(): void
    {
        $this->assertInstanceOf(Sms::class, new Sms());
    }

    public function testDeleteByIdNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $sms->deleteById('123');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testDeleteById(): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(204)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $this->assertTrue($sms->deleteById('123'));
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

    public function testGetById(): void
    {
        $responseBody = '{"id":123,"outgoing_id":5333455,"origin":"origin","destination":"destination","message":"This is a test message","status":"delivered","dateTime":"2020-08-18 17:10:35 +1000"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $smsResponse = $sms->getById('123');
            $this->assertEquals('123', $smsResponse['id']);
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

    public function testGetByIdNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $sms->getById('123');
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

    public function testSendToOne(): void
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateString = $date->format('Y-m-d H:i:s O');

        $responseBody = '{"messages":[{"id":"123","outgoing_id":1,"origin":"origin","destination":"destination","message":"This is a test message","status":"sent","dateTime":"' . $dateString . '"}]}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $smsResponse = $sms->sendToOne('destination', 'This is a test message');
            $this->assertEquals('123', $smsResponse['messages'][0]['id']);
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

    public function testSendToMultiple(): void
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateString = $date->format('Y-m-d H:i:s O');

        $responseBody = '{"messages":[{"id":"123","outgoing_id":1,"origin":"origin","destination":"destination","message":"This is a test message","status":"sent","dateTime":"' . $dateString . '"}]}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $smsResponse = $sms->sendToMultiple(['destination'], 'This is a test message');
            $this->assertEquals('123', $smsResponse['messages'][0]['id']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidPayloadException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testSendToWithOrigin(): void
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateString = $date->format('Y-m-d H:i:s O');

        $responseBody = '{"messages":[{"id":"123","outgoing_id":1,"origin":"origin","destination":"destination","message":"This is a test message","status":"sent","dateTime":"' . $dateString . '"}]}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $smsResponse = $sms->sendToOne('destination', 'This is a test message', 'origin');
            $this->assertEquals('123', $smsResponse['messages'][0]['id']);
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
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateString = $date->format('Y-m-d H:i:s O');

        $responseBody = '{"messages":[{"id":"123","outgoing_id":1,"origin":"origin","destination":"destination","message":"This is a test message","status":"sent","dateTime":"' . $dateString . '"}]}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            $smsResponse = $sms->rawPayload([
                "destination" => 'destination',
                "message" => 'This is a test message',
                "origin" => 'origin'
            ]);
            $this->assertEquals('123', $smsResponse['messages'][0]['id']);
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
            $sms = new Sms();
            // failing with using non UTF8 text.
            $sms->rawPayload(['message' => utf8_decode("ü")]);
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
            new Response(400)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $sms = new Sms($client);
            // failing with using non UTF8 text.
            $sms->rawPayload(['message' => null]);
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

}
