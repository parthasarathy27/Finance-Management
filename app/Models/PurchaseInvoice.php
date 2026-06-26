<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'purchase_invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'purchase_invoice_id');
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getOutstandingAmountAttribute()
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    public function getCalculatedStatusAttribute()
    {
        if ($this->outstanding_amount <= 0) {
            return 'Paid';
        }

        if (Carbon::parse($this->due_date)->isPast() && $this->outstanding_amount > 0) {
            return 'Overdue';
        }

        return $this->status; // Return database status or 'Pending'
    }
}
