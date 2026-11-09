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
    Schema::table('users', function (Blueprint $table) {
        $table->string('role', 20)->default('employee');
        $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
        $table->boolean('is_active')->default(true);
        $table->string('theme', 10)->default('light');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['department_id']);
        $table->dropColumn(['role', 'department_id', 'is_active', 'theme']);
    });
}
};
