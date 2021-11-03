<?php
declare(strict_types=1);

namespace Modules\Smtp\Console;

use App\Attributes\Console\Stream;
use Interfaces\Console\Handler;
use Symfony\Component\Console\Output\OutputInterface;
use Termwind\HtmlRenderer;

#[Stream(name: 'smtp')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private OutputInterface $output,
        private HtmlRenderer $renderer,
    ){
    }

    public function handle(array $payload): void
    {
        $addresses = [];
        foreach (['from', 'to', 'cc', 'bcc', 'reply_to'] as $type) {
            if (($users = $this->prepareUsers($payload, $type)) !== []) {
                $addresses[$type] = $users;
            }
        }

        $attachments = [];
        foreach ($payload['data']['attachments'] ?? [] as $i => $attachment) {
            $attachments[] = $attachment['name'];
        }

        $this->renderer->render((string) view('smtp::stream.output', [
            'id' => $payload['data']['id'],
            'date' => date('r'),
            'subject' => $payload['data']['subject'],
            'addresses' => $addresses,
            'attachments' => $attachments,
            'body' => $payload['data']['text'],
        ]));
    }

    protected function prepareUsers(array $payload, string $key): array
    {
        $users = [];
        foreach ($payload['data'][$key] as $user) {
            $users[] = $user;
        }

        return $users;
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (!$this->config->isEnabled()) {
            return true;
        }

        return !isset($payload['data']);
    }
}
