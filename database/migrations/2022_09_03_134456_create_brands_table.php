<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('brands', static function (Blueprint $table) {
			$table->id();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('logo')->nullable();

			$table->timestamps();
            $table->softDeletes();
		});
	}

	public function down()
	{
		Schema::dropIfExists('brands');
	}
};
