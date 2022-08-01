<?php

namespace Migration;

use Cycle\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrmDefaultcaf7f0112f326d4cc4b69f7612839858f extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
        });

        DB::table('projects')->insert(
            ['name' => 'default']
        );
    }

    public function down(): void
    {
        $this->table('projects')->drop();
    }
}
