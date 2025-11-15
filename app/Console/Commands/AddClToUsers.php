<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Destination;
use App\Models\CasualLeaveBalance;
use Illuminate\Support\Facades\DB;

class AddClToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-cl-to-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add CL to all users based on their destination rules and update CL table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Adding CL for all users based on destination...');

        DB::transaction(function () {
            // ✅ Load destination relation correctly
            $users = User::with('designation')->get();

            foreach ($users as $user) {
                $designation = $user?->designation;

                if (!$designation) {
                    $this->warn("⚠️  User #{$user->id} has no designation assigned, skipping.");
                    continue;
                }

                // ✅ Get CL value from designation (example: designation has 'cl' column)
                $addCl = $designation->cl ?? 0;

                if ($addCl <= 0) {
                    $this->warn("ℹ️  designation '{$designation->name}' gives 0 CL, skipping user #{$user->id}.");
                    continue;
                }

                // ✅ Find or create CL record safely
                $cl = CasualLeaveBalance::firstOrCreate(
                    ['user_id' => $user->id],
                    ['total' => 0, 'remaining' => 0]
                );

                // ✅ Increment both total and remaining CL
                $cl->increment('total', $addCl);
                $cl->increment('remaining', $addCl);

                $this->info("✅ Added {$addCl} CL to user #{$user->id} ({$designation->name})");
            }
        });

        $this->info('🎉 All users updated successfully!');
        return 0;
    }
}
