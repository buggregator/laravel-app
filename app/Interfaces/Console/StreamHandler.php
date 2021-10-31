<?php
declare(strict_types=1);

namespace Interfaces\Console;

use App\Attributes\Console\Stream;
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

    public function __construct(private OutputInterface $output, private Application $app)
    {
        $files = (new Finder())->files()->name('*.php')->in($app->basePath('app'));
        collect($files)->each(fn(SplFileInfo $file) => $this->registerStreamHandler($file));

        Termwind::renderUsing(new BrowserOutput($output));
    }

    private function registerStreamHandler(SplFileInfo $file)
    {
        $fullyQualifiedClassName = $this->fullQualifiedClassNameFromFile($file);

        $this->processAttributes($fullyQualifiedClassName);
    }

    private function processAttributes(string $className): void
    {
        if (!class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);

        $attributes = $class->getAttributes(Stream::class);

        if (!count($attributes)) {
            return;
        }

        /** @var Stream $stream */
        $stream = $attributes[0]->newInstance();

        $this->handlers[$stream->getName()] = $this->app->make($className, [
            'output' => new OutputStyle(
                new ArgvInput(),
                $this->output
            )
        ]);
    }

    private function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $path = Str::replaceFirst($this->app->basePath(), '', $file->getRealPath());
        $class = trim($path, DIRECTORY_SEPARATOR);

        return str_replace(
            [DIRECTORY_SEPARATOR, 'App\\Application\\', 'App\\'],
            ['\\', 'App\\', ''],
            ucfirst(Str::replaceLast('.php', '', $class))
        );
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
