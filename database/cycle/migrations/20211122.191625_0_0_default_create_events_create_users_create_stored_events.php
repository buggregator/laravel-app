<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultFc32e99858eb508026ddc4f952087b7b extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('events')
            ->addColumn('uuid', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('type', 'string', [
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
        
        $this->table('users')
            ->addColumn('remember_token', 'string', [
                'nullable' => false,
                'default'  => null
            ])
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
        
        $this->table('users')->drop();
        
        $this->table('events')->drop();
    }
}
