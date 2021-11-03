<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoteServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_servers', function (Blueprint $table) {
            $table->string('host')->primary();
            $table->integer('port')->default(22);
            $table->string('username');
            $table->string('ssk_key')->nullable();
            $table->boolean('is_connected')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remote_servers');
    }
}
