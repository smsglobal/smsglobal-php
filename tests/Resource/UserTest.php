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
use SMSGlobal\Resource\User;

class UserTest extends TestCase
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
        $this->assertInstanceOf(User::class, new User());
    }

    public function testGetBalanceNonPrepaid(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $user = new User($client);
            $user->getBalance();
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

    public function testGetBalance(): void
    {
        $responseBody = '{"balance":100.00,"currency":"USD"}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $user = new User($client);
            $response = $user->getBalance();
            $this->assertEquals('100', $response['balance']);
            $this->assertIsFloat($response['balance']);
        } catch (GuzzleException $e) {
            $this->fail('This test should not have failed');
        } catch (AuthenticationException $e) {
            $this->fail('This test should not have failed');
        } catch (InvalidResponseException $e) {
            $this->fail('This test should not have failed');
        } catch (ResourceNotFoundException $e) {
            $this->fail('This test should not have failed');
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

    public function testGetBalanceInvalidResponse(): void
    {
        $this->expectException(InvalidResponseException::class);

        $responseBody = '{"balance":100.00,"currency":"USD",}';

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], $responseBody)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        try {
            $user = new User($client);
            $user->getBalance();
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

}
