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

namespace Graze\Gigya\Test\Unit\Validation;

use Graze\Gigya\Exception\UnknownResponseException;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\ValidGigyaResponseMiddleware;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

class ValidGigyaResponseMiddlewareTest extends TestCase
{
    public function testValidResponse()
    {
        $handler = new MockHandler([
            new Response(200, [], TestFixtures::getFixture('accounts.search_simple')),
        ]);

        $middleware = ValidGigyaResponseMiddleware::middleware();

        $func = $middleware($handler);
        $func(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testMissingFieldWillThrowAnException()
    {
        $handler = new MockHandler([
            new Response(200, [], TestFixtures::getFixture('missing_field')),
        ]);

        $this->expectException(UnknownResponseException::class);
        $this->expectExceptionMessage("The contents of the response could not be determined. Missing required field: 'statusReason'");

        $middleware = ValidGigyaResponseMiddleware::middleware();

        $func = $middleware($handler);
        $func(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testNoBodyWillFail()
    {
        $handler = new MockHandler([
            new Response(200),
        ]);

        $this->expectException(UnknownResponseException::class);
        $this->expectExceptionMessage('The contents of the response could not be determined');

        $middleware = ValidGigyaResponseMiddleware::middleware();

        $func = $middleware($handler);
        $func(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testInvalidBody()
    {
        $handler = new MockHandler([
            new Response(200, [], TestFixtures::getFixture('invalid_json')),
        ]);

        $this->expectException(UnknownResponseException::class);
        $this->expectExceptionMessage('The contents of the response could not be determined. Could not decode the body');

        $middleware = ValidGigyaResponseMiddleware::middleware();

        $func = $middleware($handler);
        $func(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testUnknownResponseContainsTheOriginalResponse()
    {
        $response = new Response(200, [], TestFixtures::getFixture('invalid_json'));
        $handler = new MockHandler([$response]);

        $middleware = ValidGigyaResponseMiddleware::middleware();

        $func = $middleware($handler);
        try {
            $func(new Request('GET', 'https://foo.com'), [])->wait();
        } catch (UnknownResponseException $e) {
            $this->assertSame($response, $e->getResponse());
        }
    }
}
