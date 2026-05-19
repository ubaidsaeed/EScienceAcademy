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
        if(!Schema::hasTable('subject_content')){
        Schema::table('subject_content', function (Blueprint $table) {
            $table->foreignId('feature_id')->nullable()->after('subject_id')->constrained('features')->onDelete('cascade');
            $table->dropColumn('feature'); // if you had string column before
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_content', function (Blueprint $table) {
            //
        });
    }
};
