<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons');
            $table->string('couponable_type');
            $table->string('couponable_id');
            $table->char('flg_used', 1)->nullable();
            $table->timestamp('date_time_used')->nullable();
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
        Schema::dropIfExists('entity_coupons');
    }
}
