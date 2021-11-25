<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault1e5a31b92337b8a5a635afa30098a401 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('events')
            ->addColumn('uuid', 'string', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('type', 'string', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('payload', 'json', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('date', 'datetime', [
                'nullable' => false,
                'default' => null,
            ])
            ->setPrimaryKeys(["uuid"])
            ->create();
    }

    public function down(): void
    {
        $this->table('events')->drop();
    }
}
