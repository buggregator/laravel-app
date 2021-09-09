<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class JsonHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        dump(json_decode($payload['content']['value'], true));
    }
}
