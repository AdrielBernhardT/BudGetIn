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
        Schema::table('goals', function (Blueprint $table) {
            // Set once the "goal reached" notification has been sent (also marks the goal as completed)
            $table->timestamp('reached_notified_at')->nullable()->after('target_date');

            // Set once the "deadline approaching (<7 days)" reminder has been sent
            $table->timestamp('deadline_notified_at')->nullable()->after('reached_notified_at');

            // Set once the "goal missed" (deadline passed without reaching target) notification has been sent
            $table->timestamp('missed_notified_at')->nullable()->after('deadline_notified_at');

            // Date of the last "start of month" investment reminder sent for this goal
            $table->date('last_monthly_reminder_at')->nullable()->after('missed_notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn([
                'reached_notified_at',
                'deadline_notified_at',
                'missed_notified_at',
                'last_monthly_reminder_at',
            ]);
        });
    }
};
