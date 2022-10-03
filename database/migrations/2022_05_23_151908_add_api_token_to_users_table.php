<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users','api_token')) {
                $table->string('api_token', 100)->after('name')->nullable();
            }
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->removeColumn('api_token');
		});
	}
};
