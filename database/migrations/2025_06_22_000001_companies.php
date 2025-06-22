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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->string('email')->unique()->nullable(true);
            $table->string('phone', 15)->nullable(true);
            $table->string('website')->nullable(true);
            $table->string('description')->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('zip_code', 10)->nullable(false);
            $table->foreignId('upload_id')
                ->nullable()
                ->constrained('uploads')
                ->onDelete('set null');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
