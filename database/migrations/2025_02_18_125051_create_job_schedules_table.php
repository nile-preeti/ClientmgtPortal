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
        Schema::create('job_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("service_id")->nullable();
            $table->unsignedBigInteger("customer_id")->nullable();

            $table->string("frequency")->nullable();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->time("start_time")->nullable();
            $table->time("end_time")->nullable();
            $table->string("status")->nullable();
            $table->text("description")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_schedules');
    }
};
