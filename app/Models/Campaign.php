<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'goal_amount',
        'stripe_account_id',
        // optionally: 'start_date', 'end_date', 'status' etc.
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function getTotalDonatedAttribute()
    {
        return $this->donations()->where('status', 'succeeded')->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->goal_amount - $this->total_donated;
    }
}
