<?php

namespace SMSGlobal\Resource;

use GuzzleHttp\Exception\GuzzleException;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\InvalidPayloadException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

/**
 * Class Sms
 * @package SMSGlobal\Resource
 */
class Sms extends Base
{

    /**
     * @var string
     */
    private $resourceUri = '/sms';

    /**
     * @param string $to
     * @param string $text
     * @param string|null $from
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function sendToOne(string $to, string $text, string $from = null): array
    {
        $origin = !empty($from) ? $from : '';

        return $this->rawPayload([
            "destination" => $to,
            "message" => $text,
            "origin" => $origin
        ]);
    }

    /**
     * @param array $to
     * @param string $text
     * @param string|null $from
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function sendToMultiple(array $to, string $text, string $from = null): array
    {
        $origin = !empty($from) ? $from : '';

        return $this->rawPayload([
            "destinations" => json_encode($to),
            "message" => $text,
            "origin" => $origin
        ]);
    }

    /**
     * @param array $payload
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function rawPayload(array $payload): array
    {
        $jsonPayload = json_encode($payload, JSON_FORCE_OBJECT);

        if(!$jsonPayload) {
            throw new InvalidPayloadException('Invalid payload ' . json_last_error_msg());
        }

        $uri = $this->prepareApiUri($this->resourceUri);

        $this->lastResponse = $this->doCall('POST', $this->host . $uri, [
            'body' => "$jsonPayload",
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('POST', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json'
            ]
        ]);

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
    }

    /**
     * @param string $smsglobalId
     * @return mixed|null
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws ResourceNotFoundException
     * @throws InvalidResponseException
     */
    public function getById(string $smsglobalId): array
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/' . $smsglobalId);

        $this->lastResponse = $this->doCall('GET', $this->host . $uri,  [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('GET', $uri, $this->domain),
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
        $uri = $this->prepareApiUri($this->resourceUri . '/' . $smsglobalId);

        $this->lastResponse = $this->doCall('DELETE', $this->host . $uri, [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('DELETE', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json'
            ]
        ]);

        return $this->lastResponse->getStatusCode() == 204;
    }

}
