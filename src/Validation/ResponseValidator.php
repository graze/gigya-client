<?php

namespace Graze\Gigya\Validation;

use Graze\Gigya\Exceptions\InvalidTimestampException;
use Graze\Gigya\Exceptions\UnknownResponseException;
use Psr\Http\Message\ResponseInterface;

class ResponseValidator implements ResponseValidatorInterface
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @var SignatureValidator
     */
    private $signatureValidator;

    /**
     * @param string $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
        $this->signatureValidator = new SignatureValidator();
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws InvalidTimestampException
     * @throws UnknownResponseException
     */
    public function assert(ResponseInterface $response)
    {
        $data = json_decode($response->getBody(), true);
        if (!(is_array($data) && array_key_exists('statusCode', $data))) {
            throw new UnknownResponseException($response);
        }

        if ((array_key_exists('UID', $data)) &&
            (array_key_exists('UIDSignature', $data)) &&
            (array_key_exists('signatureTimestamp', $data))
        ) {
            $this->signatureValidator->assertUid(
                $data['UID'],
                $data['signatureTimestamp'],
                $this->secret,
                $data['UIDSignature']
            );
        }

        return true;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    public function validate(ResponseInterface $response)
    {
        $data = json_decode($response->getBody(), true);
        if (!(is_array($data) && array_key_exists('statusCode', $data))) {
            return false;
        }

        if ((array_key_exists('UID', $data)) &&
            (array_key_exists('UIDSignature', $data)) &&
            (array_key_exists('signatureTimestamp', $data))
        ) {
            return $this->signatureValidator->validateUid(
                $data['UID'],
                $data['signatureTimestamp'],
                $this->secret,
                $data['UIDSignature']
            );
        }

        return true;
    }
}
