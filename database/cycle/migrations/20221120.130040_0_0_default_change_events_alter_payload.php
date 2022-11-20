<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultEcdc8134797047661b676f0a3969a742 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('events')
            ->alterColumn('payload', 'longText', [
                'nullable' => false,
                'default'  => null,
            ])
            ->update();
    }

    public function down(): void
    {
    }
}
