<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('agent') && !Schema::hasColumn('agent', 'type_id')) {
            Schema::table('agent', function (Blueprint $table) {
                /*
                 * Types:
                 * 0 - legal entity
                 * 1 - individual
                 */
                $table->unsignedTinyInteger('type_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('agent') && Schema::hasColumn('agent', 'type_id')) {
            Schema::table('agent', function (Blueprint $table) {
                $table->dropColumn('type_id');
            });
        }
    }
}
