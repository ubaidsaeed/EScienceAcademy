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
        if(!Schema::hasTable('features')){
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('key',255)->unique();           // e.g., online_notes
            $table->string('name',255);                     // e.g., Online Notes
            $table->boolean('has_content')->default(false); // Does it need folder/file assignment?
            $table->string('icon')->nullable();         // FontAwesome icon
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
