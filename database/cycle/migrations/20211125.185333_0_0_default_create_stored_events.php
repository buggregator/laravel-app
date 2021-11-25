<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault1e5a31b92337b8a5a635afa30098a403 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('stored_events')
            ->addColumn('event_id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('event_type', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('aggregate_root_id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('version', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('payload', 'json', [
                'nullable' => false,
                'default'  => null
            ])
            ->setPrimaryKeys(["event_id"])
            ->create();
    }

    public function down(): void
    {
        $this->table('stored_events')->drop();
    }
}
