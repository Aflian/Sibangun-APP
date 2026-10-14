<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            $table->enum('role', ['admin', 'staff'])->default('staff')->after('password')->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role')->index();
            $table->dropColumn('email_verified_at');
            $table->string('name', 100)->change();
            $table->string('email', 150)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'role', 'status']);
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
