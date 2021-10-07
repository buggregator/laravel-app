<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidCasts implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof UuidInterface) {
            return $value->toString();
        }

        return $value;
    }
}
