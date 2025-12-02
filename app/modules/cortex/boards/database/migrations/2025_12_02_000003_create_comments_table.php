<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cortex_boards_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->text('body');

            $table->auditableAndTimestamps();
            $table->softDeletes();

            $table->foreign('post_id')->references('id')->on('cortex_boards_posts')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('cortex_boards_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cortex_boards_comments');
    }
}
