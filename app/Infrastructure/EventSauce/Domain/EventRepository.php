<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce\Domain;

use EventSauce\EventSourcing\MessageRepository;

interface EventRepository extends MessageRepository
{
}
