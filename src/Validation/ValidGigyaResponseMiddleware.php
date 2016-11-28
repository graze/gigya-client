<?php
/**
 * This file is part of graze/gigya-client
 *
 * Copyright (c) 2016 Nature Delivered Ltd. <https://www.graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://github.com/graze/gigya-client/blob/master/LICENSE.md
 * @link    https://github.com/graze/gigya-client
 */

namespace Graze\Gigya\Validation;

use Closure;
use Graze\Gigya\Exception\InvalidTimestampException;
use Graze\Gigya\Exception\UnknownResponseException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;

class ValidGigyaResponseMiddleware
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
     * @var callable
     */
    private $handler;

    /**
     * ValidGigyaResponseMiddleware constructor.
     *
     * @param callable $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Return the handler to assert that the response returned is valid
     *
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return Closure
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        $fn = $this->handler;
        return $fn($request, $options)
            ->then(
                function (GuzzleResponseInterface $response) {
                    $this->assert($response);
                    return $response;
                }
            );
    }

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
     * Returns a Middleware handler functions for this class
     *
     * @return Closure
     */
    public static function middleware()
    {
        return function (callable $handler) {
            return new static($handler);
        };
    }
}
