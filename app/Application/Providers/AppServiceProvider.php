git u<?php
declare(strict_types=1);

namespace App\Providers;

use App\Queue\RoadRunnerConnector;
use App\Session\RoadRunnerSessionHandler;
use Illuminate\Support\ServiceProvider;
use NunoMaduro\Collision\Highlighter;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\KeyValue\Factory;
use Termwind\HtmlRenderer;
use Termwind\Termwind;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['queue']->extend('roadrunner', fn() => new RoadRunnerConnector);

        CliDumper::$defaultOutput = 'php://stderr';

        $this->app['session']->extend('roadrunner', static function () {
            $factory = new Factory(RPC::create(config('roadrunner.rpc.host')));
            return new RoadRunnerSessionHandler(
                $factory->select(config('roadrunner.session.storage', 'session')),
                (int)config('session.lifetime')
            );
        });

        HtmlRenderer::extend('code', static function(\DOMNode $node) {
            $highlighter = new Highlighter();

            $line = (int) $node->getAttribute('line');
            $startLine = $node->getAttribute('start-line');
            $startLine = $startLine ===  '' ? 0 : max($startLine, 1);

            $html = array_reduce(
                iterator_to_array($node->childNodes),
                static fn(string $html, \DOMNode $child) => $html .= $child->ownerDocument->saveXML($child),
                ''
            );

            $linesBefore = 4;
            $linesAfter = 4;

            $html = html_entity_decode($html);

            if ($startLine > 0) {
                $lines = preg_split('/\r\n|\r|\n/', $html);
                $totalLines = count($lines);

                if ($line > $startLine) {
                    $linesBefore = $line - $startLine - 1;
                } elseif ($line === $startLine) {
                    $linesBefore = 0;
                }

                $linesAfter = 4;
                if ($line < $totalLines) {
                    $linesAfter = count($lines) - $line - 1;
                } elseif ($line === $totalLines) {
                    $linesAfter = 0;
                }
            }

            return Termwind::raw(
                rtrim($highlighter->getCodeSnippet(html_entity_decode($html), $line, $linesBefore, $linesAfter))
            );
        });
    }
}
