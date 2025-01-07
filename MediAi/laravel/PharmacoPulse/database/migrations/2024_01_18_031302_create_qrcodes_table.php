<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id(); // This is equivalent to `qr_code_id INT PRIMARY KEY AUTO_INCREMENT`
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('prescription_id')->nullable(); // Add this line
            $table->string('qr_code_value', 255);
            $table->timestamps(); // Adds created_at and updated_at columns

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('set null'); // Add this line
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
    }
};
