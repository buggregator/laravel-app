<?php
declare(strict_types=1);

namespace App\TCP;

use Spiral\RoadRunner\Tcp\Request;
use Symfony\Component\Console\Output\OutputInterface;

interface Handler
{
    public function handle(Request $request, OutputInterface $output): ?Response;
}
