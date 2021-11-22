<?php

declare(strict_types=1);

namespace App\Contracts\EventSource;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

interface Event extends SerializablePayload
{
}
