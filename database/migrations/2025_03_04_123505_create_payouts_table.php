<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->timestamp("payment_date")->nullable();
            $table->float("total_amount")->nullable();
            $table->float("admin_charges")->nullable();
            $table->float("driver_charges")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
