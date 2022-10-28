<?php

declare(strict_types=1);

namespace Modules\Sentry\Interfaces\Http\Controllers;

use App\Commands\FindTransactionByName;
use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Contracts\Query\QueryBus;
use App\Exceptions\EntityNotFoundException;
use Cycle\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Stream;
use Http\Message\Encoding\GzipDecodeStream;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Modules\Sentry\Contracts\EventHandler;
use Modules\Transaction\Domain\Transaction;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\Console\Output\ConsoleOutput;

class EnvelopeAction extends Controller
{
    #[Post(uri: 'api/{projectId}/envelope', name: 'sentry.event.envelope')]
    public function __invoke(Request $request,
                             CommandBus $commands,
                             EventHandler $handler,
                             QueryBus $queryBus,
                             EntityManagerInterface $entityManager,
                             ConsoleOutput $output): void
    {
        $stream = new GzipDecodeStream(
            new Stream($request->getContent(true))
        );

        $content = $stream->getContents();
        $lines = explode("\n", $content);
        $data = [];
        foreach ($lines as $line) {
            if (! empty($line)) {
                $data[] = json_decode($line, true);
            }
        }

        if (count($data) == 3) {
            $contentTypeInfo = $data[1];
            if ($contentTypeInfo['type'] === 'transaction') {
                $eventContent = $data[2];
                $event = $handler->handle($eventContent);
                $projectId = request()->route()->parameter('projectId');
                $transactionName = $eventContent['transaction'];
                try {
                    $transaction = $queryBus->ask(new FindTransactionByName($transactionName));
                } catch (EntityNotFoundException) {
                    $transaction = new Transaction($transactionName);
                    $entityManager->persist($transaction);
                    $entityManager->run();
                }
                $commands->dispatch(
                    new HandleReceivedEvent((int) $projectId, 'sentryTransaction', $event, true, $transaction->getId())
                );
            } elseif ($contentTypeInfo['type'] === 'session') {
                //TODO
            }
        }
    }
}
