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
        Schema::create('course_completion_trackings', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('course_content_id');
            $table->unsignedBigInteger('member_id');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('course_members')->onDelete('cascade');
            $table->foreign('course_content_id')->references('id')->on('course_contents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_completion_trackings');
    }
};
