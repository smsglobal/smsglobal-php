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
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;
use SMSGlobal\Resource\SmsIncoming;

class SmsIncomingTest extends TestCase
{

    protected $credentials;

    protected $apiKey = 'key12345';
    protected $apiSecret = 'secret12345';

    public function setUp(): void
    {
        $this->credentials =  Credentials::set($this->apiKey, $this->apiSecret);
    }

    public function testSmsIncomingInstance(): void
    {
        $this->assertInstanceOf(SmsIncoming::class, new SmsIncoming());
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
            $smsIncoming = new SmsIncoming($client);
            $smsIncoming->deleteById('123');
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
            $smsIncoming = new SmsIncoming($client);
            $this->assertTrue($smsIncoming->deleteById('123'));
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testGetById(): void
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateString = $date->format('Y-m-d H:i:s O');

        $responseBody = '{"id":123,"origin":"origin","destination":"destination","message":"Test incoming sms","dateTime":"' . $dateString . '","isMultipart":false}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $smsIncoming = new SmsIncoming($client);
            $smsResponse = $smsIncoming->getById('123');
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
            $smsIncoming = new SmsIncoming($client);
            $smsResponse = $smsIncoming->getById('123');
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

}
