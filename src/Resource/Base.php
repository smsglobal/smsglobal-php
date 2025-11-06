<?php

namespace SMSGlobal\Resource;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use SMSGlobal\Credentials;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\CredentialsException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\PaymentRequiredException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

/**
 * Class Base
 * @package SMSGlobal\Resource
 */
class Base
{
    /** @var string Date format accepted by the server */
    const string DATE_FORMAT = 'Y-m-d H:i:s';

    const string CLIENT_VERSION = '1.0.4';

    /**
     * @var Credentials|null
     */
    protected ?Credentials $credentials = null;

    /**
     * @var string
     */
    protected string $version = 'v2';

    /**
     * @var string
     */
    protected string $domain = 'api.smsglobal.com';

    /**
     * @var string
     */
    protected string $host = 'https://api.smsglobal.com';

    /**
     * @var string
     */
    protected string $userAgent = "SMSGlobal-SDK/v2 Version/" . self::CLIENT_VERSION . " PHP/" . PHP_VERSION . " (" . PHP_OS . "; " . OPENSSL_VERSION_TEXT. ")";

    /**
     * @var Client|ClientInterface|null
     */
    protected $client;

    /**
     * @var ResponseInterface|null
     */
    public ?ResponseInterface $lastResponse = null;

    /**
     * Base constructor.
     * @param ClientInterface|null $client
     * @throws CredentialsException
     */
    public function __construct(?ClientInterface $client = null)
    {
        $this->credentials = Credentials::get();
        $this->client = $client ?: new Client();
    }

    /**
     * @param string $resourceUri
     * @return string
     */
    protected function prepareApiUri(string $resourceUri): string
    {
        return '/' . $this->version . $resourceUri;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws PaymentRequiredException
     * @throws ResourceNotFoundException
     */
    protected function doCall(string $method, string $url, array $options = []): ResponseInterface
    {
        try {
            $response = $this->client->request($method, $url, $options);
        } catch (GuzzleException $e) {
            if ($e->getCode() == 402) {
                throw new PaymentRequiredException($e->getMessage());
            }
            if ($e->getCode() == 403) {
                throw new AuthenticationException($e->getMessage());
            }
            if ($e->getCode() == 404){
                throw new ResourceNotFoundException($e->getMessage());
            }

            throw $e;
        }

        return $response;
    }

    /**
     * @param string $jsonString
     * @return array
     * @throws InvalidResponseException
     */
    protected function getJsonDecode(string $jsonString): array
    {
         $arr = json_decode($jsonString, true);
         if (is_null($arr)) {
             throw new InvalidResponseException('Invalid JSON response string');
         }

         return $arr;
    }
}
