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

namespace Graze\Gigya\Test;

use GuzzleHttp\Psr7\Stream;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
    }

    /**
     * @param string $text
     *
     * @return Stream
     */
    protected function toStream($text)
    {
        $stream = fopen('php://temp', 'a+');
        fwrite($stream, $text);
        rewind($stream);
        return new Stream($stream);
    }
}
