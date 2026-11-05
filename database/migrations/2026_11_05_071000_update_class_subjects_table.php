<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('class_subjects', 'section_id')) {
                $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
            }
            if (!Schema::hasColumn('class_subjects', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            }
            if (!Schema::hasColumn('class_subjects', 'is_elective')) {
                $table->boolean('is_elective')->default(false);
            }
        });
    }

    public function down()
    {
    }
};

