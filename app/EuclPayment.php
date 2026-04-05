<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class EuclPayment extends Model
{
    use HasFactory;

    protected $table = 'eucl_payments'; // Ensure this matches the actual table name
    
    protected $fillable = [
        'request',
        'uuid',
        'amount',
        'token',
        'meter_number',
        'vendor',
        'regulatory',
        'tokenexplanation',
        'reciept_number',
        'response',
        'units',
        'status',
        'created_by',

        
        

    ];



}




