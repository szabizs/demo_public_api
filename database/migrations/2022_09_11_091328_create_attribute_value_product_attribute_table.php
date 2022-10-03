<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('attribute_value_product_attribute', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('attribute_value_id');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values');

            $table->unsignedBigInteger('product_attribute_id');
            $table->foreign('product_attribute_id')->references('id')->on('product_attributes');

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('attribute_value_product_attribute');
	}
};
