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
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The returned array keys MUST map to an event name. Each array value
     * MUST be an array in which the first element is the name of a function
     * on the EventSubscriber OR an array of arrays in the aforementioned
     * format. The second element in the array is optional, and if specified,
     * designates the event priority.
     *
     * For example, the following are all valid:
     *
     *  - ['eventName' => ['methodName']]
     *  - ['eventName' => ['methodName', $priority]]
     *  - ['eventName' => [['methodName'], ['otherMethod']]
     *  - ['eventName' => [['methodName'], ['otherMethod', $priority]]
     *  - ['eventName' => [['methodName', $priority], ['otherMethod', $priority]]
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
