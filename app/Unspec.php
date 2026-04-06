<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;


class Unspec extends Model
{
    protected $table = 'unspec';

    /**
     * Unspec Dropdown
     *
     */
    public static function forDropdown()
    {
        try {
            $unspec = Unspec::orderBy('item_class', 'asc')->get();
            return $unspec->pluck('item_class', 'unspec_code');
        } catch (\Exception $e) {
            return collect();
        }
    }


   

}
