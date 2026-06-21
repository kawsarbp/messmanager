<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropUnique(['member_id', 'date', 'type']);
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->unique(['member_id', 'date', 'type']);
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
        });
    }
};
