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

namespace Graze\Gigya\Auth;

use Closure;
use Psr\Http\Message\RequestInterface;

class HttpsAuthMiddleware
{
    const AUTH_NAME = 'gigya';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var null|string
     */
    private $userKey;
    /**
     * @var callable
     */
    private $nextHandler;

    /**
     * @param callable    $nextHandler
     * @param string      $apiKey
     * @param string      $secret
     * @param string|null $userKey
     */
    public function __construct(callable $nextHandler, $apiKey, $secret, $userKey = null)
    {
        $this->nextHandler = $nextHandler;
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->userKey = $userKey;
    }

    /**
     * Inject the https auth into the query string
     *
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return Closure
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        if ($request->getUri()->getScheme() == 'https' && $options['auth'] == static::AUTH_NAME) {
            $params = array_merge(
                \GuzzleHttp\Psr7\parse_query($request->getBody()),
                [
                    'apiKey' => $this->apiKey,
                    'secret' => $this->secret,
                ]
            );

            if ((bool)$this->userKey) {
                $params['userKey'] = $this->userKey;
            }
            $request = $request
                ->withBody(\GuzzleHttp\Psr7\stream_for(http_build_query($params)));
        }
        $fn = $this->nextHandler;
        return $fn($request, $options);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string|null
     */
    public function getUserKey()
    {
        return $this->userKey;
    }

    /**
     * Return a middleware handler function for https Authentication
     *
     * @param string      $apiKey
     * @param string      $secret
     * @param string|null $userKey
     *
     * @return Closure
     */
    public static function middleware($apiKey, $secret, $userKey = null)
    {
        return function (callable $handler) use ($apiKey, $secret, $userKey) {
            return new static($handler, $apiKey, $secret, $userKey);
        };
    }
}
