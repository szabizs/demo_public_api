<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

if(!function_exists('getEnumValues')) {
    function getEnumValues($table, $column) {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type ;

        preg_match('/^enum\((.*)\)$/', $type, $matches);

        $enum_values = array();
        foreach( explode(',', $matches[1]) as $value )
        {
            $v = trim( $value, "'" );
            $enum_values = Arr::add($enum_values, $v, $v);
        }

        asort($enum_values);

        return $enum_values;
    }
}
