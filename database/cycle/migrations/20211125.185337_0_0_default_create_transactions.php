<?php

namespace Migration;

use Cycle\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrmDefaultdf242a80322ea14e8c12bb36ece75dae extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false)->unique();
        });
    }

    public function down(): void
    {
        $this->table('transactions')->drop();
    }
}
