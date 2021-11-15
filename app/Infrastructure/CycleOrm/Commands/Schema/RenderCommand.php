<?php
declare(strict_types=1);

namespace Infrastructure\CycleOrm\Commands\Schema;

use Illuminate\Console\Command;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Infrastructure\CycleOrm\SchemaManager;

final class RenderCommand extends Command
{
    protected $signature = 'cycle:schema:render';
    protected $description = 'Render CycleORM schemas';

    public function handle(SchemaManager $schemaManager, OutputSchemaRenderer $renderer)
    {
        $this->output->write(
            $renderer->render(
                $schemaManager->compileSchema()
            )
        );
    }
}
