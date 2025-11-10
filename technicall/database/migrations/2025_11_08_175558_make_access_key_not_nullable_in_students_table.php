<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any null values (shouldn't exist, but just in case)
        DB::statement('UPDATE students SET access_key = UUID() WHERE access_key IS NULL');

        Schema::table('students', function (Blueprint $table) {
            // Change the column to NOT NULL (unique constraint already exists, so don't add it again)
            $table->string('access_key')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert to nullable (unique constraint already exists, so don't add it again)
            $table->string('access_key')->nullable()->change();
        });
    }
};
