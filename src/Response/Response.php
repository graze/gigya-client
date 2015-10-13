<?php

namespace Graze\Gigya\Response;

use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;
use Illuminate\Support\Collection;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class Response implements ResponseInterface
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @var array
     */
    protected $body;

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var string|null
     */
    protected $errorMessage;

    /**
     * @var string|null
     */
    protected $errorDetails;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $statusReason;

    /**
     * @var string
     */
    protected $callId;

    /**
     * @var DateTimeImmutable
     */
    protected $time;

    /**
     * @var Collection
     */
    protected $data;

    /**
     * @var GuzzleResponseInterface
     */
    protected $response;

    /**
     * @param GuzzleResponseInterface $response
     */
    public function __construct(GuzzleResponseInterface $response)
    {
        $this->response     = $response;
        $this->body         = json_decode($response->getBody());
        $this->errorCode    = (int) $this->popField('errorCode');
        $this->errorMessage = $this->popField('errorMessage');
        $this->errorDetails = $this->popField('errorDetails');
        $this->statusCode   = (int) $this->popField('statusCode');
        $this->statusReason = $this->popField('statusReason');
        $this->callId       = $this->popField('callId');
        $this->time         = DateTimeImmutable::createFromFormat(static::DATE_TIME_FORMAT, $this->popField('time'));
    }

    /**
     * Get a field from the body if it exists, and remove the name from the array.
     *
     * @param string $name
     *
     * @return mixed|null The value or null if the field does not exist
     */
    public function popField($name)
    {
        if (property_exists($this->body, $name)) {
            $value = $this->body->{$name};
            unset($this->body->{$name});

            return $value;
        }

        return;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return string|null
     */
    public function getErrorDetails()
    {
        return $this->errorDetails;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusReason()
    {
        return $this->statusReason;
    }

    /**
     * @return string
     */
    public function getCallId()
    {
        return $this->callId;
    }

    /**
     * @return DateTimeInterface
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return Collection
     */
    public function getData()
    {
        if (! $this->data) {
            $this->data = new Collection($this->body);
        }

        return $this->data;
    }

    /**
     * @return GuzzleResponseInterface
     */
    public function getOriginalResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "Response: %d: %s - %d: %s\n%s\n%s\n%s",
            $this->getStatusCode(),
            $this->getStatusReason(),
            $this->getErrorCode(),
            ErrorCode::getName($this->getErrorCode()),
            ErrorCode::getDescription($this->getErrorCode()),
            $this->getErrorMessage(),
            $this->getErrorDetails()
        );
    }
}
