<?php
declare(strict_types=1);

namespace App\Monolog\Console;

use App\Attributes\Console\Stream;
use App\Console\Handler;
use Carbon\Carbon;
use NunoMaduro\Collision\ConsoleColor;
use Symfony\Component\Console\Output\OutputInterface;

#[Stream(name: 'monolog')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private OutputInterface $output,
        private ConsoleColor $color,
    )
    {
    }

    public function handle(array $payload): void
    {
        $this->output->table([], [
            ['date', Carbon::parse($payload['data']['datetime'])->format('r')],
            ['channel', $payload['data']['channel'] ?? ''],
        ]);

        $style = match (strtolower($payload['data']['level_name'])) {
            'notice' , 'info' => 'bg_light_blue',
            'warning' => 'bg_yellow',
            'critical', 'error', 'alert', 'emergency' => 'bg_red',
            default => 'bg_dark_gray'
        };

        $this->output->writeln(sprintf(
            '  <fg=white;bg=blue;options=bold> %s </>%s',
            'MONOLOG', $this->color->apply($style, ' ' . $payload['data']['level_name'] . ' ' ?? ' DEBUG ')
        ));

        $this->output->newLine();

        foreach (explode("\n", $payload['data']['message']) as $line) {
            $this->output->writeln(sprintf(' <fg=default;options=bold> %s </>  ', $line));
        }

        $this->output->newLine();

        if (!empty($payload['data']['context'])) {
            dump($payload['data']['context']);

            $this->output->newLine();
        }
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (!$this->config->isEnabled()) {
            return true;
        }

        return !isset($payload['data']['message']);
    }
}
