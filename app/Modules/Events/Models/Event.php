<?php
declare(strict_types=1);

namespace Modules\Events\Models;

use App\Models\UuidCasts;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const UPDATED_AT = null;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $casts = [
        'uuid' => UuidCasts::class,
        'event' => 'string',
        'payload' => 'json'
    ];
}
