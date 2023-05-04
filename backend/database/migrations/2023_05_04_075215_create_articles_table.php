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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('writer_user_id')->nullable();
            $table->foreign('writer_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('editor_user_id')->nullable();
            $table->foreign('editor_user_id')->references('id')->on('users');
            $table->foreignId('company_id')->nullable()->constrained();
            $table->string('image');
            $table->string('path');
            $table->string('title');
            $table->string('link');
            $table->timestamp('date');
            $table->text('content');
            $table->enum('status', ['For Edit', 'Published']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
