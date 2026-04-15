<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->foreignId('specialization_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('level_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['specialization_id']);
            $table->dropForeign(['level_id']);
            $table->dropColumn(['specialization_id', 'level_id']);
        });
    }
};
