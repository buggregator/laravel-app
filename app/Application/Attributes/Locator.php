<?php

declare(strict_types=1);

namespace App\Attributes;

use Generator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class Locator
{
    public function __construct(private Application $app)
    {
    }

    /**
     * @return array<ReflectionClass, ReflectionAttribute[]>
     */
    public function findClassAttributes(string $directory, string $attribute): Generator
    {
        foreach ($this->findClasses($directory) as $class) {
            $attributes = $class->getAttributes($attribute);

            if (! count($attributes)) {
                continue;
            }

            yield $class => $attributes;
        }
    }

    /**
     * @return array<ReflectionMethod, ReflectionAttribute[]>
     */
    public function findClassMethodsAttributes(string $directory, string $attribute): Generator
    {
        foreach ($this->findClasses($directory) as $class) {
            $methods = $class->getMethods();

            foreach ($methods as $method) {
                $attributes = $method->getAttributes($attribute);

                if (! count($attributes)) {
                    continue;
                }

                yield $method => $attributes;
            }
        }
    }

    private function findClasses(string $directory): Generator
    {
        $files = (new Finder())
            ->files()
            ->name('*.php')
            ->in($this->app->basePath($directory));

        foreach ($files as $file) {
            $className = $this->fullQualifiedClassNameFromFile($file);

            if (! class_exists($className)) {
                continue;
            }

            $class = new ReflectionClass($className);

            yield $className => $class;
        }
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
}
