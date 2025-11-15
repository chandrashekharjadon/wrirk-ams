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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Fields you requested
            $table->string('aadhar', 20)->nullable();
            $table->string('pan', 15)->nullable();
            $table->string('acc_no', 30)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->date('joining_date')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('address')->nullable();
            $table->string('pin_code', 10)->nullable();

            // Extra useful fields
            $table->string('profile_photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
