<?php

declare(strict_types=1);

namespace Modules\RemoteServers\Models;

use Illuminate\Database\Eloquent\Model;

class RemoteServer extends Model
{
    protected $table = 'remote_servers';
    public $incrementing = false;
    protected $keyType = 'string';
}
