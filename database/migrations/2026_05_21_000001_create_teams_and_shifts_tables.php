<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('role')->default('employee')->after('team_id');
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('location')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedSmallInteger('slots')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('shift_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('confirmed');
            $table->timestamps();

            $table->unique(['shift_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_user');
        Schema::dropIfExists('shifts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_id');
            $table->dropColumn('role');
        });
        Schema::dropIfExists('teams');
    }
};
