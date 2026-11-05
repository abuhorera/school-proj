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
        Schema::table('id_card_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('id_card_templates', 'width_mm')) {
                $table->decimal('width_mm', 6, 2)->default(54.00)->after('layout');
            }
            if (!Schema::hasColumn('id_card_templates', 'height_mm')) {
                $table->decimal('height_mm', 6, 2)->default(85.60)->after('width_mm');
            }
            if (!Schema::hasColumn('id_card_templates', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('height_mm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('id_card_templates', function (Blueprint $table) {
            $table->dropColumn(['width_mm', 'height_mm', 'is_default']);
        });
    }
};
