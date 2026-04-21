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
        // Get manager role ID
        $managerRole = \DB::table('roles')->where('code', 'manager')->first();
        
        if ($managerRole) {
            // Get admin role ID
            $adminRole = \DB::table('roles')->where('code', 'admin')->first();
            
            if ($adminRole) {
                // Update all users with manager role to have admin role instead
                \DB::table('role_utilisateur')
                    ->where('role_id', $managerRole->id)
                    ->update(['role_id' => $adminRole->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            //
        });
    }
};
