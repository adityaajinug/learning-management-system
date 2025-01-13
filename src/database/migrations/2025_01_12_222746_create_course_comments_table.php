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
        Schema::create('course_comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->boolean('visible')->default(false);
            $table->unsignedBigInteger('content_id');
            $table->unsignedBigInteger('member_id');
            $table->timestamps();

            $table->foreign('content_id')->references('id')->on('course_contents')->onDelete('restrict');
            $table->foreign('member_id')->references('id')->on('course_members')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_comments');
    }
};
