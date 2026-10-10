<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * ONE-TIME FIX — restores the admin role for the admin account.
 *
 * Root cause: Onboarding.php was overwriting the role column to 'provider'
 * unconditionally, including for admin accounts that visited the page to test.
 *
 * This migration is safe to run multiple times (idempotent).
 * After deploying, delete this file and push again to keep migrations clean.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'admin@kz.com')
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // Intentionally empty — do not reverse an admin role restoration.
    }
};
