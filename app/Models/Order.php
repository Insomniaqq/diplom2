<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'supplier_id',
        'purchase_request_id',
        'total_amount',
        'status',
        'expected_delivery_date',
        'actual_delivery_date',
        'notes',
        'created_by',
        'is_archived',
        'archived_at',
        'payment_terms',
        'shipping_address',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function archive()
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);
    }

    public function unarchive()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }
}
