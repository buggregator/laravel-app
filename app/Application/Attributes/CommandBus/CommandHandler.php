<?php

declare(strict_types=1);

namespace App\Attributes\CommandBus;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CommandHandler
{
}
