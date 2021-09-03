<?php
declare(strict_types=1);

namespace App\Smtp\Console;

use App\Attributes\Console\Stream;
use App\Console\Handler;
use Symfony\Component\Console\Output\OutputInterface;

#[Stream(name: 'smtp')]
class StreamHandler implements Handler
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function handle(array $payload): void
    {
        $this->output->table([], [
            ['date', date('r')],
            ['id', $payload['data']['id']],
        ]);

        $this->output->writeln(sprintf(
            '  <fg=white;bg=blue;options=bold> %s </>',
            'SMTP'
        ));

        $this->output->newline();

        if (isset($payload['data']['subject'])) {
            $this->output->writeln("  Subject: <fg=default;options=bold>  {$payload['data']['subject']}</>");
        }

        $data = [];
        foreach (['from', 'to', 'cc', 'bcc', 'reply_to'] as $type) {
            if ($result = $this->prepareUsers($payload, $type)) {
                $data[] = [$type, $result];
            }
        }

        foreach ($payload['data']['attachments'] ?? [] as $i => $attachment) {
            $data[] = ['attachment ' . $i + 1 . '.', sprintf('%s', $attachment['name'])];
        }

        $this->output->table([], $data);

        $this->output->newline();
        if (!empty($payload['data']['text'])) {
            $this->output->writeln($payload['data']['text']);
        }

    }

    protected function prepareUsers(array $payload, string $key): ?string
    {
        $string = [];
        foreach ($payload['data'][$key] as $user) {
            $string[] = sprintf('%s <%s>', $user['name'], $user['email']);
        }

        return empty($string) ? null : implode("\n", $string);
    }

    public function shouldBeSkipped(array $payload): bool
    {
        return !isset($payload['data']);
    }
}
