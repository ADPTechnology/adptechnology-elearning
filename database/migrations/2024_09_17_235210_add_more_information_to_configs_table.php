<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreInformationToConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->string('address')->nullable()->after('whatsapp_number');
            $table->string('tik_tok_link')->nullable()->after('instagram_link');
            $table->string('youtube_link')->nullable()->after('instagram_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn(['address', 'tik_tok_link', 'youtube_link']);
        });
    }
}
