<?php
declare(strict_types=1);

namespace App\TCP;

interface Response
{
    public function getContext(): string;
    public function getBody(): string;
}
