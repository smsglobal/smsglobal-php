<?php

namespace SMSGlobal;

use SMSGlobal\Exceptions\CredentialsException;

/**
 * Class Credentials
 * @package SMSGlobal
 */
class Credentials
{
    /**
     * Hash Algorithm for API Authentication
     */
    const HASH_ALGO = 'sha256';

    /**
     * @var string
     */
    private $apiKey = null;

    /**
     * @var string
     */
    private $secretKey = null;

    /**
     * @var Credentials
     */
    private static $credentials;

    /**
     * @param $apiKey
     */
    private function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $apiSecretKey
     */
    private function setSecretKey($apiSecretKey)
    {
        $this->secretKey = $apiSecretKey;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    private function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * Credentials constructor.
     * @param string $apiKey
     * @param string $apiSecret
     */
    static public function set(string $apiKey, string $apiSecret)
    {
        if (is_null(self::$credentials)) {
            self::$credentials = new Credentials();
        }

        self::$credentials->setApiKey($apiKey);
        self::$credentials->setSecretKey($apiSecret);
    }

    /**
     * @return Credentials
     * @throws CredentialsException
     */
    static public function get()
    {
        if(is_null(self::$credentials)) {
            throw new CredentialsException('Credentials not set');
        }

        return self::$credentials;
    }

    /**
     * Gets the value to use for the Authorization header
     *
     * @param string $method HTTP method (e.g. GET)
     * @param string $requestUri Request URI (e.g. /v2/sms/)
     * @param string $host Hostname
     * @return string
     */
    public function getAuthorizationHeader(string $method, string $requestUri, string $host): string
    {
        $timestamp = time();
        $nonce = md5(microtime() . mt_rand());

        $hash = self::hashRequest($timestamp, $nonce, $method, $requestUri, $host);
        $header = 'MAC id="%s", ts="%s", nonce="%s", mac="%s"';
        $header = sprintf($header, $this->getApiKey(), $timestamp, $nonce, $hash);

        return $header;
    }

    /**
     * Hashes a request using the API secret, for use in the Authorization
     * header
     *
     * @param int $timestamp Unix timestamp of request time
     * @param string $nonce Random unique string
     * @param string $method HTTP method (e.g. GET)
     * @param string $requestUri Request URI (e.g. /v1/sms/)
     * @param string $host Hostname
     * @param int $port Port (e.g. 443)
     * @return string
     */
    private function hashRequest(int $timestamp, string $nonce, string $method, string $requestUri, string $host, int $port = 443)
    {
        $string = array($timestamp, $nonce, $method, $requestUri, $host, $port, '');
        $string = sprintf("%s\n", implode("\n", $string));
        $hash = hash_hmac(self::HASH_ALGO, $string, $this->getSecretKey(), true);
        $hash = base64_encode($hash);
        return $hash;
    }

}
