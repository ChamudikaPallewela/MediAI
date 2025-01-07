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
        Schema::create('medicine_usages', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('drug_id');
            $table->date('usage_date'); // To store the actual date of usage
            $table->string('month'); // To store the month as a string (e.g., "January")
            $table->timestamps(); // Automatically adds created_at and updated_at columns

            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_usage');
    }

};
