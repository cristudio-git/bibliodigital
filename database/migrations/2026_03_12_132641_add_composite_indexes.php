<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->index(['uploaded_by', 'deleted_at', 'created_at'], 'idx_books_uploaded_by_deleted_created');
            $table->index(['type', 'deleted_at', 'created_at'], 'idx_books_type_deleted_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'active', 'created_at'], 'idx_users_role_active_created');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('idx_books_uploaded_by_deleted_created');
            $table->dropIndex('idx_books_type_deleted_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role_active_created');
        });
    }
};
