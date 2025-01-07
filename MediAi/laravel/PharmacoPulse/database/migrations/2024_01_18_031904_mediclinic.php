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
        Schema::create('medicine_clinic', function (Blueprint $table) {
            $table->unsignedBigInteger('drug_id');
            $table->unsignedBigInteger('clinic_id');
            $table->primary(['drug_id', 'clinic_id']);
            $table->foreign('drug_id')->references('id')->on('drugs');
            $table->foreign('clinic_id')->references('id')->on('clinics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_clinic');
    }
};
