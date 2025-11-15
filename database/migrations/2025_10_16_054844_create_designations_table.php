<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade'); // one department can have many designations
            $table->string('name'); // e.g., Manager, Developer, HR Executive
            $table->string('code')->unique()->nullable(); // Optional short code, e.g. MGR01
            $table->integer('cl')->default(0); // Casual leave allowance or similar
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};

