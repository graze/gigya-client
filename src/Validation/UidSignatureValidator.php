<?php

namespace Graze\Gigya\Validation;

use Graze\Gigya\Exception\InvalidTimestampException;
use Graze\Gigya\Exception\InvalidUidSignatureException;
use Graze\Gigya\Response\ResponseInterface;

/**
 * Class UidSignatureValidator.
 */
class UidSignatureValidator implements ResponseValidatorInterface
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @var Signature
     */
    private $signature;

    /**
     * @param Signature $signature
     * @param string    $secret
     */
    public function __construct(Signature $signature, $secret)
    {
        $this->secret    = $secret;
        $this->signature = $signature;
    }

    /**
     * Can validate.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function canValidate(ResponseInterface $response)
    {
        $data = $response->getData();

        return ($data->has('UID') &&
            $data->has('UIDSignature') &&
            $data->has('signatureTimestamp'));
    }

    /**
     * Throws exceptions if any errors are found.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function validate(ResponseInterface $response)
    {
        $data = $response->getData();

        return $this->validateUid(
            $data->get('UID'),
            $data->get('signatureTimestamp'),
            $data->get('UIDSignature')
        );
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws InvalidTimestampException
     * @throws InvalidUidSignatureException
     *
     * @return void
     */
    public function assert(ResponseInterface $response)
    {
        $data = $response->getData();

        $this->assertUid(
            $data->get('UID'),
            $data->get('signatureTimestamp'),
            $data->get('UIDSignature'),
            $response
        );
    }

    /**
     * Validate the provided Uid signature is valid.
     *
     * @param string $uid
     * @param int    $timestamp Unix Timestamp
     * @param string $signature
     *
     * @return bool
     */
    public function validateUid($uid, $timestamp, $signature)
    {
        return ($this->signature->checkTimestamp($timestamp) &&
            $signature == $this->signature->getUidSignature($uid, $timestamp, $this->secret));
    }

    /**
     * @param string            $uid
     * @param int               $timestamp Unix Timestamp
     * @param string            $signature
     * @param ResponseInterface $response
     *
     * @throws InvalidTimestampException
     * @throws InvalidUidSignatureException
     *
     * @return bool
     */
    private function assertUid($uid, $timestamp, $signature, ResponseInterface $response)
    {
        if (!$this->signature->checkTimestamp($timestamp)) {
            throw new InvalidTimestampException($timestamp, $response);
        }
        $expected = $this->signature->getUidSignature($uid, $timestamp, $this->secret);
        if ($signature !== $expected) {
            throw new InvalidUidSignatureException($uid, $expected, $signature, $response);
        }

        return true;
    }
}
