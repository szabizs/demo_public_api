<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('product_images', function (Blueprint $table) {
			$table->boolean('main')->after('product_id')->default(false);
		});
	}

	public function down()
	{
		Schema::table('product_images', function (Blueprint $table) {
			$table->dropColumn('main');
		});
	}
};
