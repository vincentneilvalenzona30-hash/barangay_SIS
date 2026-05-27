<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! DB::table('users')->where('role', 'admin')->exists()) {
            $firstUser = DB::table('users')->orderBy('id')->first();

            if ($firstUser) {
                DB::table('users')
                    ->where('id', $firstUser->id)
                    ->update(['role' => 'admin']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
