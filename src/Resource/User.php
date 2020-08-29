<?php

namespace SMSGlobal\Resource;

use GuzzleHttp\Exception\GuzzleException;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

/**
 * Class User
 * @package SMSGlobal\Resource
 */
class User extends Base
{

    /**
     * @var string
     */
    private $resourceUri = '/user';

    /**
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function getBalance(): array
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/credit-balance');

        $this->lastResponse = $this->doCall('GET', $this->host . $uri,  [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('GET', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json'
            ]
        ]);

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
    }

}
