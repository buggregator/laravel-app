<?php
declare(strict_types=1);

namespace Modules\Inspector\Console;

use App\Attributes\Console\Stream;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Interfaces\Console\Handler;
use NunoMaduro\Collision\ConsoleColor;
use Symfony\Component\Console\Output\OutputInterface;

#[Stream(name: 'inspector')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private OutputInterface     $output,
        private ConsoleColor        $color,
    )
    {
    }


    public function handle(array $payload): void
    {
        $requestData = $payload['data'][0];

        dump($payload);
        $this->output->table([], [
            ['date', Carbon::createFromTimestamp($requestData['timestamp'])->format('r')],
            ['hostname', $requestData['host']['hostname'] ?? $requestData['host']['ip']],
            ['duration', ($requestData['duration'] ?? 0) . ' ms'],
            ['memory peak', ($requestData['memory_peak'] ?? 0) . ' mb'],
        ]);

        $statusCode = (int)$requestData['result'] ?? 0;

        $style = match (true) {
            $statusCode >= 400 && $statusCode < 500 => 'bg_yellow',
            $statusCode >= 300 && $statusCode < 400 => 'bg_light_blue',
            $statusCode >= 500 => 'bg_red',
            default => 'bg_green'
        };

        $this->output->writeln(sprintf(
            '  <fg=white;bg=blue;options=bold> %s </>%s',
            'INSPECTOR', $this->color->apply($style, ' ' .$statusCode . ' : ' . Response::$statusTexts[$statusCode])
        ));


        $this->output->newLine();
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (!$this->config->isEnabled()) {
            return true;
        }

        return empty($payload['data']);
    }
}
