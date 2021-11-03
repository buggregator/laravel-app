<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;

class EloquentModelHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'class' => $payload['content']['class_name'],
            'attributes' => VariableCleaner::clean($payload['content']['attributes'], 0)
        ];
    }

    public function shouldBeSkipped(array $payload): bool
    {
        return empty($payload['content']) || parent::shouldBeSkipped($payload);
    }
}
