<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault1e5a31b92337b8a5a635afa30098a402 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
            ->addColumn('remember_token', 'string', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('uuid', 'string', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('name', 'string', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('password', 'string', [
                'nullable' => false,
                'default' => null,
            ])
            ->addIndex(["name"], [
                'name' => 'users_index_name_619fdbaa03862',
                'unique' => true,
            ])
            ->setPrimaryKeys(["uuid"])
            ->create();
    }

    public function down(): void
    {
        $this->table('users')->drop();
    }
}
