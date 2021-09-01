<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class ExceptionHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $data = [
            ['Class', $payload['content']['class']],
            ['Message', $payload['content']['message']],
        ];

        foreach ($payload['content']['frames'] as $i => $frame) {
            $row = $frame['file_name'] . ':' . $frame['line_number'];
            if ($i === 0) {
                $row .= "\n";

                foreach ($frame['snippet'] as $line) {
                    $lineNumber = $line['line_number'] . '.';
                    if ($lineNumber == $frame['line_number']) {
                        $lineNumber .= ' ==> ';
                    }

                    $row .= $lineNumber . str_repeat(' ', 10 - strlen($lineNumber));
                    $row .= ' ' . $line['text'];
                    $row .= "\n";
                }
            }

            $data[] = ['', $row];
        }


        $this->output->table([], $data);
    }

    public function printTitle(array $payload): void
    {
        $this->output->error(
            $payload['content']['message']
        );
    }
}
