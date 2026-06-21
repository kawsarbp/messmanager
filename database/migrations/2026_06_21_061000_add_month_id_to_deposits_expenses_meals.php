<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->foreignId('month_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('month_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->foreignId('month_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropConstrainedForeignId('month_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('month_id');
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('month_id');
        });
    }
};
