<?php
declare(strict_types=1);

namespace Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const UPDATED_AT = null;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $casts = [
        'event' => 'string',
        'payload' => 'json'
    ];
}
