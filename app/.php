<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class RRAimports extends Model
{
    use HasFactory;

    protected $table = 'rraimports'; // Ensure this matches the actual table name
    protected $fillable = ['id', 'hs_cd', 'dcl_de', 'created_at'];
}




