<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable=[
        'registration_id',
        'invoice_id',
        'payment_date',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class,'registration_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
}
