<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault8877be1e68e0997ca52bf481a4c02950 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('events')
            ->addColumn('uuid', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('event', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('payload', 'json', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('date', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->setPrimaryKeys(["uuid"])
            ->create();
        
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
        
        $this->table('events')->drop();
    }
}
