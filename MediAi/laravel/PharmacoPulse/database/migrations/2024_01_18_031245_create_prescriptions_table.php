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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id(); // This is equivalent to `prescription_id INT PRIMARY KEY AUTO_INCREMENT`
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('drug_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('dosage')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('drug_id')->references('id')->on('drugs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};