<?php
declare(strict_types=1);

namespace Interfaces\Console;

use App\Attributes\Console\Stream;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use NunoMaduro\Collision\Adapters\Laravel\ExceptionHandler;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class StreamHandler implements Handler
{
    private array $handlers = [];

    public function __construct(private OutputInterface $output, private Application $app)
    {
        $files = (new Finder())->files()->name('*.php')->in($app->basePath('app'));
        collect($files)->each(fn(SplFileInfo $file) => $this->registerStreamHandler($file));
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
            'output' => $this->output
        ]);
    }

    private function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = trim(Str::replaceFirst($this->app->basePath(), '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        return str_replace(
            [DIRECTORY_SEPARATOR, 'App\\Application\\', 'App\\'],
            ['\\', 'App\\', ''],
            ucfirst(Str::replaceLast('.php', '', $class))
        );
    }

    public function handle(array $payload): void
    {
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
