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
        Schema::create('wfhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // link to users
            $table->date('date'); // WFH date
            $table->decimal('percent', 5, 2)->default(100); // percentage of day worked from home
            $table->time('check_in')->nullable();  // optional in-time
            $table->time('check_out')->nullable(); // optional out-time
            $table->decimal('working_hours', 5, 2)->nullable();
            $table->string('remark')->nullable(); //Remarks....
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wfhs');
    }
};
