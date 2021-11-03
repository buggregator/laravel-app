<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;

class ViewHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'path' =>  $payload['content']['view_path_relative_to_project_root'],
            'data' => VariableCleaner::clean($payload['content']['data'], 0)
        ];
    }
}
