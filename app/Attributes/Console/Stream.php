<?php
declare(strict_types=1);

namespace App\Attributes\Console;

use Attribute;

#[Attribute]
class Stream
{
    public function __construct(protected string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
