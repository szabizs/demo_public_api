<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('attribute_values', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');

            $table->text('value');

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('attribute_values');
	}
};
