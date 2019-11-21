<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResizedImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resized_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_url');
            $table->float('max_width');
            $table->float('max_height');
            $table->integer('user_id')->nullable()->unsigned();
            //$table->binary('image_content');
            $table->string('image_type');
            $table->string('random_generated_path')->unique();
            $table->timestamps();
        });

       DB::statement("ALTER TABLE resized_images ADD image_content MEDIUMBLOB NOT NULL ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resized_images');
    }
}
