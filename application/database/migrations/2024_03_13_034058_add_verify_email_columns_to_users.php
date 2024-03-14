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
        Schema::table('users', function (Blueprint $table) {
            $table->string('verify_email_code', 8)->nullable()->after('email_verified_at');
            $table->dateTime('verify_email_expired_at')->nullable()->after('verify_email_code');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verify_email_code');
            $table->dropColumn('verify_email_expired_at');
        });
    }
};
