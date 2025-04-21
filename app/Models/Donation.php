<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id', 'amount', 'payment_status', 'payment_intent_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
