<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'unit_of_measure',
        'min_quantity',
        'current_quantity',
        'price',
    ];

    protected $casts = [
        'min_quantity' => 'decimal:2',
        'current_quantity' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function distributions()
    {
        return $this->hasMany(MaterialDistribution::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class)->withPivot('monthly_quantity')->withTimestamps();
    }
}
