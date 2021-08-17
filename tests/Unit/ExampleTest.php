<?php

namespace Tests\Unit;

use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\SocketHandler;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        logger()->debug('test');
        $handler = new SlackWebhookHandler('http://127.0.0.1:8000/slack');

        $handler->handle(['message' => 'hello', 'level' => 'debug', 'extra' => [], 'context' => []]);
    }
}
