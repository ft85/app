<?php

namespace App\Restaurant;

use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResTable extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Most recent transaction on this table (draft or final).
     */
    public function activeTransaction()
    {
        return $this->hasOne(Transaction::class, 'res_table_id')
            ->latest();
    }

    /**
     * Currently assigned waiter
     */
    public function assignedWaiter()
    {
        return $this->belongsTo(\App\User::class, 'assigned_waiter_id');
    }
}
