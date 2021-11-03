<?php
declare(strict_types=1);

namespace Modules\VarDumper\Console;

use App\Events\EventReceived;
use App\TCP\CloseConnection;
use App\TCP\ContinueRead;
use App\TCP\Handler;
use App\TCP\Response;
use App\Websocket\BrowserOutput;
use Illuminate\Contracts\Events\Dispatcher;
use Spiral\RoadRunner\Tcp\Request;
use Spiral\RoadRunner\Tcp\TcpWorkerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\Stub;
use Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class TcpHandler implements Handler
{
    public function __construct(
        private Dispatcher $events, private StreamHandlerConfig $config)
    {
    }

    public function handle(Request $request, OutputInterface $output): Response
    {
        if ($request->event === TcpWorkerInterface::EVENT_CONNECTED) {
            return new ContinueRead();
        }

        $messages = array_filter(explode("\n", $request->body));

        foreach ($messages as $message) {
            $payload = @unserialize(base64_decode($message), ['allowed_classes' => [Data::class, Stub::class]]);

            // Impossible to decode the message, give up.
            if (false === $payload) {
                throw new \RuntimeException("Unable to decode a message from [{$request->connectionUuid}] client.");
            }

            if (!is_array($payload) || count($payload) < 2 || !$payload[0] instanceof Data || !is_array($payload[1])) {
                throw new \RuntimeException("Invalid payload from [{$request->connectionUuid}] client.");
            }

            $this->fireEvent($payload);
            $this->sendToConsole($request, $payload, new BrowserOutput($output));
        }

        return new CloseConnection();
    }

    private function sendToConsole(Request $request, array $payload, OutputInterface $output): void
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $descriptor = new CliDescriptor(new CliDumper());

        [$data, $context] = $payload;

        $context['cli']['identifier'] = $request->connectionUuid;
        $context['cli']['command_line'] = $request->remoteAddr;

        $descriptor->describe(new SymfonyStyle(new ArrayInput([]), $output), $data, $context, 0);
    }

    private function fireEvent(array $payload): void
    {
        $this->events->dispatch(new EventReceived('var-dump', [
            'payload' => [
                'type' => $payload[0]->getType(),
                'value' => $this->convertToPrimitive($payload[0])
            ],
            'context' => $payload[1]
        ]));
    }

    private function convertToPrimitive(Data $data): string|null
    {
        if (in_array($data->getType(), ['string', 'boolean'])) {
            return (string) $data->getValue();
        }

        $dumper = new HtmlDumper();

        return $dumper->dump($data, true);
    }
}
