<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('attributes', function (Blueprint $table) {
			$table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->enum('frontend_type', ['checkbox', 'radio', 'text', 'textarea']);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(false);

			$table->timestamps();
            $table->softDeletes();
		});
	}

	public function down()
	{
		Schema::dropIfExists('attributes');
	}
};
