<?php

namespace App\Websocket;

use App\Events\Websocket\TerminalWrite;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class BrowserOutput extends Output
{
    public function __construct(private OutputInterface $output)
    {
        parent::__construct(decorated: true);
    }

    public function write($messages, bool $newline = false, int $options = self::OUTPUT_NORMAL)
    {
        $this->output->write($messages, $newline, $options);
        parent::write($messages, $newline, $options);
    }

    protected function doWrite(string $message, bool $newline)
    {
        event(new TerminalWrite($message, $newline));
    }
}
