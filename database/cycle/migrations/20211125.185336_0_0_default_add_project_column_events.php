<?php

namespace Migration;

use Cycle\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrmDefaultc2397d7f9a11fa76c17f0e54cb7b9aea extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
