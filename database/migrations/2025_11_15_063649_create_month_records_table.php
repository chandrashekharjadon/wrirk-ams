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
        Schema::create('month_records', function (Blueprint $table) {
            $table->id();

            // 🧑 User Relation
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // 💰 Salary Structure
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->decimal('basic', 10, 2)->default(0);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('conveyance', 10, 2)->default(0);
            $table->decimal('simple_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);

            // ➕ Additional Earnings
            $table->decimal('incentive', 10, 2)->default(0);  
            $table->decimal('reward', 10, 2)->default(0);

            // 📅 Period (Month & Year)
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');

            // ⏱ Attendance & Leave Summary
            $table->float('working_hours')->default(0);
            $table->float('working_days')->default(0);
            $table->integer('half_days')->default(0);
            $table->integer('leaves')->default(0);
            $table->integer('sandwitch')->default(0);
            $table->integer('wfh')->default(0);

            // 🌴 Casual Leave Info
            $table->decimal('used_cl', 5, 2)->default(0);
            $table->decimal('available_cl', 5, 2)->default(0);
            $table->decimal('total_cl', 5, 2)->default(0);   // ⭐ ADDED NEW FIELD HERE

            // 💻 WFH Details
            $table->json('wfh_dates')->nullable();
            $table->json('wfh_percentages')->nullable();
            $table->json('wfh_prices')->nullable();
            $table->decimal('total_wfh_cost', 10, 2)->default(0);

            // 🛑 WFH Deduction Cost
            $table->decimal('wfh_deduction_cost', 10, 2)->default(0);

            // 🔁 Alternative Pay (Variable Pay)
            $table->decimal('alternative_variable_pay', 10, 2)->default(0);
            $table->decimal('alternative_variable_loses', 10, 2)->default(0);
            $table->decimal('total_earning', 10, 2)->default(0);

            // 💸 Deductions
            $table->decimal('leave_deduction', 10, 2)->default(0);
            $table->decimal('half_day_deduction', 10, 2)->default(0);
            $table->decimal('total_deduction', 10, 2)->default(0);

            // 🏦 PF & TAX
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('tds', 10, 2)->default(0);

            // ✅ Final Computation
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->decimal('daily_rate', 10, 2)->default(0);

            $table->enum('status', [
                'not approved',
                'approved',
                'update and approved'
            ])->default('not approved');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('month_records');
    }
};
