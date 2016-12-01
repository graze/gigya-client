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

namespace Graze\Gigya\Auth\OAuth2;

use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;

class OAuth2Middleware
{
    const AUTH_NAME = 'gigya-oauth2';

    /** @var GrantInterface */
    private $grant;
    /** @var string|null */
    private $name;
    /**
     * @var callable
     */
    private $nextHandler;

    /**
     * @param callable       $nextHandler
     * @param GrantInterface $grant
     * @param string|null    $name
     */
    public function __construct(callable $nextHandler, GrantInterface $grant, $name = null)
    {
        $this->nextHandler = $nextHandler;
        $this->grant = $grant;
        $this->name = $name ?: static::AUTH_NAME;
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        if ($request->getUri()->getScheme() == 'https' && $options['auth'] == $this->name) {
            $token = $this->grant->getToken();

            if (!is_null($token)) {
                $request = $request->withHeader('Authorization', sprintf('OAuth %s', $token->getToken()));
            }
        }

        $fn = $this->nextHandler;

        return $fn($request, $options)
            ->then($this->refreshToken($request, $options));
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return Closure A function taking a response and returning a new response|future
     */
    private function refreshToken(RequestInterface $request, array $options)
    {
        /**
         * Take a response and if it requires authentication, retry the request. Otherwise pass through the response
         *
         * @param GuzzleResponseInterface $response
         *
         * @return GuzzleResponseInterface
         */
        return function (GuzzleResponseInterface $response) use ($request, $options) {
            if ($response->getStatusCode() == 401
                && $this->canRetry($request, $options)) {
                if (!is_null($this->grant->getToken())) {
                    $options['retries'] = (isset($options['retries'])) ? $options['retries'] + 1 : 1;
                    return $this($request, $options);
                }
            }
            return $response;
        };
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return bool
     */
    private function canRetry(RequestInterface $request, array $options)
    {
        return ($request->getUri()->getScheme() == 'https'
            && $options['auth'] == $this->name
            && (!isset($options['retries']) || $options['retries'] === 0));
    }

    /**
     * Return a middleware handler function for OAuth2 Authentication
     *
     * @param GrantInterface $grant
     * @param string|null    $name
     *
     * @return Closure
     */
    public static function middleware(GrantInterface $grant, $name = null)
    {
        return function (callable $handler) use ($grant, $name) {
            return new static($handler, $grant, $name);
        };
    }
}
