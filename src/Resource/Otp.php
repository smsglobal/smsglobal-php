<?php

namespace SMSGlobal\Resource;

use Cassandra\Date;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\InvalidPayloadException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

/**
 * Class Otp
 *
 * This api resource is currently a beta release
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
     * @version beta
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
     * Verify an OTP code entered by your customer using request ID received upon sending an OTP
     *
     * @param string $requestId Request ID
     * @param string $code      OTP code
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidPayloadException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version beta
     */
    public function verifyByRequestId(string $requestId, string $code)
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/requestid/' . $requestId . '/validate');

        return $this->verify($uri, $code);
    }

    /**
     * Verify an OTP code entered by your customer using destination number
     *
     * @param string $destination Destination number
     * @param string $code
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version beta
     */
    public function verifyByDestination(string $destination, string $code)
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/' . $destination . '/validate');

        return $this->verify($uri, $code);
    }

    /**
     *
     * @param string $uri  Request path
     * @param string $code OTP code
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidPayloadException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version beta
     */
    private function verify($uri, $code)
    {
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

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
    }

    /**
     * Cancel an OTP request using request ID received upon sending an OTP
     *
     * @param string $requestId
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version beta
     */
    public function cancelByRequestId(string $requestId)
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/requestid/' . $requestId . '/cancel');

        return $this->cancel($uri);
    }

    /**
     * Cancel an OTP request using destination number
     *
     * @param string $destination Destination number
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version beta
     */
    public function cancelByDestination($destination)
    {
        $uri = $this->prepareApiUri($this->resourceUri . '/' . $destination . '/cancel');

        return $this->cancel($uri);
    }

    /**
     * @param string $uri Request path
     *
     * @return array
     * @throws AuthenticationException
     * @throws InvalidResponseException
     * @throws ResourceNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version beta
     */
    private function cancel($uri)
    {
        $this->lastResponse = $this->doCall('POST', $this->host . $uri, [
            'headers' => [
                'Authorization' => $this->credentials->getAuthorizationHeader('POST', $uri, $this->domain),
                'user-agent' => $this->userAgent,
                'content-type' => 'application/json',
            ],
        ]);

        return $this->getJsonDecode($this->lastResponse->getBody()->getContents());
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
     * @version beta
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