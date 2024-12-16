<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200);
            $table->string('type', 20);
            $table->string('amount_type', 20);
            $table->decimal('amount', 8, 2);
            $table->date('expired_date');
            $table->char('active', 1);
            $table->char('especify_courses', 1);
            $table->char('especify_users', 1);
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
        Schema::dropIfExists('coupons');
    }
}
