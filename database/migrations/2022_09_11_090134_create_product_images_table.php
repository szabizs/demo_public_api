<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('product_images', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('product_id')->index();
            $table->string('thumbnail')->nullable();
            $table->string('full')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('product_images');
	}
};
