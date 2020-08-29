<?php

namespace SMSGlobal\Resource;

use GuzzleHttp\Exception\GuzzleException;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

/**
 * Class SmsIncoming
 * @package SMSGlobal\Resource
 */
class SmsIncoming extends Base
{

    /**
     * @var string
     */
    private $resourceUri = '/sms-incoming';

    /**
     * @param string $smsglobalId
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function getById(string $smsglobalId): array
    {
        $incomingUri = $this->prepareApiUri($this->resourceUri . '/' . $smsglobalId);

        $this->lastResponse = $this->doCall('GET', $this->host . $incomingUri,  [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('GET', $incomingUri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json'
            ]
        ]);

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
    }

    /**
     * @param string $smsglobalId
     * @return bool
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws ResourceNotFoundException
     */
    public function deleteById(string $smsglobalId): bool
    {
        $incomingUri = $this->prepareApiUri($this->resourceUri . '/' . $smsglobalId);

        $this->lastResponse = $this->doCall('DELETE', $this->host . $incomingUri, [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('DELETE', $incomingUri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json'
            ]
        ]);

        return $this->lastResponse->getStatusCode() == 204;
    }

}
