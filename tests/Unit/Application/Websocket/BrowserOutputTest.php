<?php

declare(strict_types=1);

namespace Application\Websocket;

use App\Events\Websocket\TerminalWrite;
use App\Websocket\BrowserOutput;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

class BrowserOutputTest extends TestCase
{
    public function testMessageShouldBroadcast()
    {
        Broadcast::shouldReceive('queue')->once()->withArgs(function (TerminalWrite $event) {
            return $event->message === 'Hello world' && $event->newline === true;
        });

        $output = new BrowserOutput(
            $buffer = new BufferedOutput()
        );

        $output->writeln('Hello world');

        $this->assertSame("Hello world\n", $buffer->fetch());
    }
}
