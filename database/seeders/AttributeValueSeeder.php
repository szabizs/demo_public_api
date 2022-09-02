<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
	public function run()
	{
        $colors = ['red', 'white', 'blue', 'orange', 'green'];
        $packaging = ['1L', '4L', '5L', '20L', '208L'];

        foreach ($packaging as $unit)
        {
            AttributeValue::create([
                'attribute_id'      =>  1,
                'value'             =>  $unit,
            ]);
        }

        foreach ($colors as $color)
        {
            AttributeValue::create([
                'attribute_id'      =>  2,
                'value'             =>  $color,
            ]);
        }
	}
}
