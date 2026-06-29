<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'sub_admin'])->default('sub_admin')->after('password');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('role');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->unsignedInteger('login_count')->default(0)->after('last_login_at');
            $table->foreignId('created_by')->nullable()->after('login_count')->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        // Pre-RBAC, every existing row had unrestricted access. Without this
        // backfill, restoring this migration against an already-populated
        // database would instantly lock every existing admin down to the
        // column default (sub_admin, zero permissions). A fresh install has
        // no rows yet at this point (seeders run after migrate) — that path
        // is covered separately by UserFactory's default role.
        DB::table('users')->update(['role' => 'super_admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['role', 'status', 'last_login_at', 'login_count', 'created_by']);
            $table->dropSoftDeletes();
        });
    }
};
