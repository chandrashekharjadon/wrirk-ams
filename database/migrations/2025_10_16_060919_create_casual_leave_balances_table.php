<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('casual_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Employee
            $table->integer('total')->default(0);    // Total casual leave allocated
            $table->integer('used')->default(0);     // Used casual leave
            $table->integer('remaining')->default(0); // Remaining casual leave
            $table->year('year');                     // Year for which balance applies
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casual_leave_balances');
    }
};

