<?php

namespace SMSGlobal\Tests;

use PHPUnit\Framework\TestCase;
use SMSGlobal\Credentials;
use SMSGlobal\Exceptions\CredentialsException;

/**
 * Class CredentialsTest
 * @package SMSGlobal\Tests
 */
class CredentialsTest extends TestCase
{

    public function testCredentialsGet(): void
    {
        $this->expectException(CredentialsException::class);
        Credentials::get();
    }

    public function testInstance(): void
    {
        Credentials::set('API_KEY', 'SECRET_KEY');
        try {
            $this->assertInstanceOf(Credentials::class, Credentials::get());
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed.');
        }
    }

    public function testGetApiKey(): void
    {
        Credentials::set('API_KEY', 'SECRET_KEY');
        try {
            $this->assertEquals('API_KEY', Credentials::get()->getApiKey());
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed.');
        }
    }

    public function testGetAuthorizationHeaderWithPost(): void
    {
        Credentials::set('API_KEY', 'SECRET_KEY');
        try {
            $authHeader = Credentials::get()->getAuthorizationHeader('POST', '/v2/sms/', 'api.smsglobal.com');
            $this->assertIsString($authHeader);
            $this->assertStringMatchesFormat('MAC id="%s", ts="%s", nonce="%s", mac="%s"', $authHeader);
        } catch (CredentialsException $e) {
            $this->fail('This test should not have failed');
        }
    }

}
