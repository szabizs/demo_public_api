<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('roles', function (Blueprint $table) {
			$table->timestamp('deleted_at')->nullable()->after('updated_at');
		});
	}

	public function down()
	{
		Schema::table('roles', function (Blueprint $table) {
			$table->removeColumn('deleted_at');
		});
	}
};
