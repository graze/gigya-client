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
use Psr\Http\Message\RequestInterface;

class ValidGigyaResponseMiddlewareTest extends TestCase
{
    public function testValidResponse()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                return new Response(200, [], TestFixtures::getFixture('accounts.search_simple'));
            },
        ]);

        $m = ValidGigyaResponseMiddleware::middleware();

        $f = $m($h);
        $f(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testMissingFieldWillThrowAnException()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                return new Response(200, [], TestFixtures::getFixture('missing_field'));
            },
        ]);

        $this->expectException(UnknownResponseException::class);
        $this->expectExceptionMessage("The contents of the response could not be determined. Missing required field: 'statusReason'");

        $m = ValidGigyaResponseMiddleware::middleware();

        $f = $m($h);
        $f(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testNoBodyWillFail()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                return new Response(200);
            },
        ]);

        $this->expectException(UnknownResponseException::class);
        $this->expectExceptionMessage('The contents of the response could not be determined');

        $m = ValidGigyaResponseMiddleware::middleware();

        $f = $m($h);
        $f(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testInvalidBody()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                return new Response(200, [], TestFixtures::getFixture('invalid_json'));
            },
        ]);

        $this->expectException(UnknownResponseException::class);
        $this->expectExceptionMessage('The contents of the response could not be determined. Could not decode the body');

        $m = ValidGigyaResponseMiddleware::middleware();

        $f = $m($h);
        $f(new Request('GET', 'https://foo.com'), [])->wait();
    }

    public function testUnknownResponseContainsTheOriginalResponse()
    {
        $response = new Response(200, [], TestFixtures::getFixture('invalid_json'));
        $h = new MockHandler([
            function (RequestInterface $request, array $options) use ($response) {
                return $response;
            },
        ]);

        $m = ValidGigyaResponseMiddleware::middleware();

        $f = $m($h);
        try {
            $f(new Request('GET', 'https://foo.com'), [])->wait();
        } catch (UnknownResponseException $e) {
            $this->assertSame($response, $e->getResponse());
        }
    }
}
