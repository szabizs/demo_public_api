<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
	public function run()
	{
        Attribute::create([
            'code' => 'packaging',
            'name' => 'Packaging',
            'frontend_type' => 'checkbox',
            'is_required' => true,
            'is_filterable' => true,
        ]);

        Attribute::create([
            'code'          =>  'color',
            'name'          =>  'Color',
            'frontend_type' =>  'checkbox',
            'is_required'   =>  true,
            'is_filterable' =>  true,
        ]);
	}
}
