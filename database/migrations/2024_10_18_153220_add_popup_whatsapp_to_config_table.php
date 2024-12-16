<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPopupWhatsappToConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->string('message_hover_whatsapp')->default('¿Necesitas ayuda?')->nullable();
            $table->string('text_whatsapp')->default('Hola 👋 ¿En qué podemos ayudarte?')->nullable();
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
            $table->dropColumn(['message_hover_whatsapp', 'text_whatsapp']);
        });
    }
}
