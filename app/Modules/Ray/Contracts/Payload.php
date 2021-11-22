<?php

declare(strict_types=1);

namespace Modules\Ray\Contracts;

interface Payload
{
    public const TYPE_CREATE_LOCK = 'create_lock';
    public const TYPE_CLEAR_ALL = 'clear_all';
}
