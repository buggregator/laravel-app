<?php
declare(strict_types=1);

namespace Interfaces\Console;

use App\Attributes\Console\Stream;
use App\Attributes\Locator;
use App\Websocket\BrowserOutput;
use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use NunoMaduro\Collision\Adapters\Laravel\ExceptionHandler;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Termwind\Termwind;

class StreamHandler implements Handler
{
    private array $handlers = [];

    public function __construct(
        private OutputInterface $output,
        private Application     $app,
        private Locator         $attributesLocator
    )
    {
        foreach ($attributesLocator->findClassAttributes('app', Stream::class) as $class => $attributes) {
            $this->processAttributes($class, $attributes);
        }

        Termwind::renderUsing(new BrowserOutput($output));
    }

    private function processAttributes(\ReflectionClass $class, array $attributes): void
    {
        /** @var Stream $stream */
        $stream = $attributes[0]->newInstance();

        $this->handlers[$stream->getName()] = $this->app->make($class->getName(), [
            'output' => new OutputStyle(
                new ArgvInput(),
                $this->output
            )
        ]);
    }

    public function handle(array $payload): void
    {
        if ($this->shouldBeSkipped($payload)) {
            return;
        }

        try {
            $this->handlers[$payload['type']]->handle($payload);
        } catch (\Throwable $e) {
            app(ExceptionHandler::class)->renderForConsole($this->output, $e);
        }
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (!isset($payload['type'])) {
            return true;
        }

        if (!isset($this->handlers[$payload['type']])) {
            return true;
        }

        return $this->handlers[$payload['type']]->shouldBeSkipped($payload);
    }
}
