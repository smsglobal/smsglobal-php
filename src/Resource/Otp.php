<?php

namespace SMSGlobal\Resource;

use Cassandra\Date;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\InvalidPayloadException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

/**
 * Class Otp
 * @package SMSGlobal\Resource
 */
class Otp extends Base
{
    /**
     * @var string
     */
    protected $resourceUri = '/otp';

    /**
     * @param string $to
     * @param string $text
     * @param string|null $from
     * @param int $codeExpiry
     * @param int $length
     * @param \DateTime|null $messageExpiryDateTime
     *
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function send(string $to, string $text, string $from = null, string $codeExpiry = null, string $length = null, \DateTime $messageExpiryDateTime = null): array
    {
        $origin = !empty($from) ? $from : '';
        $codeExpiry = !empty($codeExpiry) ? $codeExpiry : '';
        $length = !empty($length) ? $length : '';
        $messageExpiryDateTime = !empty($messageExpiryDateTime) ? $messageExpiryDateTime->format(self::DATE_FORMAT) : '';

        return $this->rawPayload([
            "destination" => $to,
            "message" => $text,
            "origin" => $origin,
            "codeExpiry" => $codeExpiry,
            "length" => $length,
            "messageExpiryDateTime" => $messageExpiryDateTime,
        ]);
    }

    /**
     * @param string $requestId
     * @param string $code
     *
     * @return bool|array Returns true if OTP input code is verified successfully otherwise error message
     * @throws AuthenticationException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verfiyById(string $requestId, string $code)
    {
        $origin = !empty($from) ? $from : '';

        $uri = $this->prepareApiUri($this->resourceUri . '/' . $requestId);

        $jsonPayload = json_encode(compact('code'), JSON_FORCE_OBJECT);

        if (!$jsonPayload) {
            throw new InvalidPayloadException('Invalid payload ' . json_last_error_msg());
        }

        $this->lastResponse = $this->doCall('POST', $this->host . $uri, [
            'body' => "$jsonPayload",
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('POST', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json',
            ],
        ]);

        return $this->lastResponse->getStatusCode() === 204;
    }

    /**
     * @param string $requestId
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById(string $requestId): array
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/' . $requestId);

        $this->lastResponse = $this->doCall('GET', $this->host . $uri, [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('GET', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json',
            ],
        ]);

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
    }

    /**
     * @param string $requestId
     *
     * @return bool|array Returns true if OTP cancelled successfully otherwise error message
     * @throws AuthenticationException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelById(string $requestId)
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/' . $requestId . '/cancel');

        $this->lastResponse = $this->doCall('PUT', $this->host . $uri, [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('PUT', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json',
            ],
        ]);

        return $this->lastResponse->getStatusCode() === 204;
    }

    /**
     * Send OTP with raw payload; Destination and message are required.
     *
     *
     * @param array $payload
     *
     * @return array|null returns null in the case of 204
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     */
    public function rawPayload(array $payload)
    {
        if (isset($payload['messageExpiryDateTime']) && $payload['messageExpiryDateTime'] instanceof \DateTime) {
            $payload['messageExpiryDateTime'] = $payload['messageExpiryDateTime']->format(self::DATE_FORMAT);
        }

        $jsonPayload = json_encode($payload, JSON_FORCE_OBJECT);

        if (!$jsonPayload) {
            throw new InvalidPayloadException('Invalid payload ' . json_last_error_msg());
        }

        $uri = $this->prepareApiUri($this->resourceUri);

        $this->lastResponse = $this->doCall('POST', $this->host . $uri, [
            'body' => "$jsonPayload",
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('POST', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json',
            ],
        ]);

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
    }

}