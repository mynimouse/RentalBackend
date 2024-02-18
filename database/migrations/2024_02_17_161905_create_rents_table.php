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
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant');
            $table->string('no_car');
            $table->dateTime('date_borrow');
            $table->dateTime('date_return');
            $table->integer('down_payment');
            $table->integer('discount')->nullable();
            $table->integer('total');
            $table->timestamps();

            $table->foreign('tenant')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
