<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->integer('feed_id')->unsigned();
            $table->foreign('feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('cascade');;
            $table->text('title');
            $table->text('link');
            $table->text('description');
            $table->dateTimeTz('pubDate', 0);
            $table->text('source');
            $table->text('source_url');
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
        Schema::dropIfExists('feed_items');
    }
}
