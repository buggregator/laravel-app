<?php

namespace Migration;

use Cycle\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrmDefault0641f6cb99c887da09f11538fe9bfe59 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
}
