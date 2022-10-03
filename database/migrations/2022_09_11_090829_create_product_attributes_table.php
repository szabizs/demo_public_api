<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('product_attributes', function (Blueprint $table) {
			$table->id();

			$table->integer('quantity');
            $table->decimal('price')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('product_attributes');
	}
};
