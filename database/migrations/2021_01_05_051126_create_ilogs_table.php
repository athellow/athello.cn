<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ilogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->text('content');
            $table->string('images', 512)->default('');
            $table->decimal('latitude', 10, 6)->default(0)->comment('纬度');
            $table->decimal('longitude', 10, 6)->default(0)->comment('经度');
            $table->unsignedInteger('praise_num')->default(0)->comment('点赞数');
            $table->boolean('is_draft')->default(false)->comment('是否是草稿');
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ilogs');
    }
}
