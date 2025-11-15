<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add department_id
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('employee_code')
                  ->constrained()
                  ->nullOnDelete();

            // Add designation_id
            $table->foreignId('designation_id')
                  ->nullable()
                  ->after('department_id')
                  ->constrained('designations')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['designation_id']);
            $table->dropColumn(['department_id', 'designation_id']);
        });
    }
};
