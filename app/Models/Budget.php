<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getSpentAmountAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->amount - $this->spent_amount);
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->amount <= 0) {
            return 0;
        }
        return round(($this->spent_amount / $this->amount) * 100, 2);
    }
}
