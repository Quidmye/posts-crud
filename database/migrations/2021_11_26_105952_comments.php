<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('user_name', 255);
            $table->integer('level')->default(0);
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('comment_id')->index()->nullable();
            $table->timestamps();
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('Cascade');
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
