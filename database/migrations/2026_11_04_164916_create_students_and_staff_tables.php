<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('SCHOOL_ADMIN')->after('email');
            $table->unsignedBigInteger('school_id')->nullable()->after('role');
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('admission_no')->unique();
            $table->string('roll_no')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender')->default('Male');
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('active'); // active, inactive, transferred
            $table->timestamps();
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->date('enrolled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('designation'); // Teacher, Principal, Accountant, Librarian, Clerk
            $table->string('department')->nullable(); // Science, Mathematics, Humanities, Administration
            $table->string('qualification')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->string('status')->default('active'); // active, on_leave, resigned
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
        Schema::dropIfExists('student_enrollments');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('students');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'school_id']);
        });
    }
};
