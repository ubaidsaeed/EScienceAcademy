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
        Schema::table('files', function (Blueprint $table) {
            $table->boolean('is_vimeo')->default(false)->after('extension');
            $table->string('vimeo_id')->nullable()->after('is_vimeo');
            $table->text('vimeo_url')->nullable()->after('vimeo_id');
            $table->text('vimeo_thumbnail_url')->nullable()->after('vimeo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['is_vimeo', 'vimeo_id', 'vimeo_url', 'vimeo_thumbnail_url']);
        });
    }
};
