<?php

namespace Graze\Gigya\Validation;

use Graze\Gigya\Exception\InvalidTimestampException;
use Graze\Gigya\Exception\UnknownResponseException;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ValidGigyaResponseSubscriber implements SubscriberInterface
{
    /**
     * @var string[]
     */
    private $requiredFields = [
        'errorCode',
        'statusCode',
        'statusReason',
        'callId',
        'time',
    ];

    /**
     * @param GuzzleResponseInterface $response
     *
     * @throws InvalidTimestampException
     * @throws UnknownResponseException
     *
     * @return void
     */
    private function assert(GuzzleResponseInterface $response)
    {
        $data = json_decode($response->getBody(), true);
        if (!is_array($data)) {
            throw new UnknownResponseException($response, 'Could not decode the body');
        }

        foreach ($this->requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new UnknownResponseException($response, "Missing required field: '{$field}'");
            }
        }
    }

    /**
     * List of events to listen for
     *
     * @return array
     */
    public function getEvents()
    {
        return ['complete' => ['onComplete', RequestEvents::VERIFY_RESPONSE]];
    }

    /**
     * When the response is complete, validate it against our current knowledge of what a gigya response shoud look
     * like.
     *
     * @param CompleteEvent $event
     * @param string        $name
     *
     * @throws InvalidTimestampException
     * @throws UnknownResponseException
     */
    public function onComplete(CompleteEvent $event, $name)
    {
        $response = $event->getResponse();

        if (is_null($response)) {
            throw new UnknownResponseException($response, 'No response provided');
        }

        $this->assert($response);
    }
}
