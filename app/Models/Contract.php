<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'number',
        'date_start',
        'date_end',
        'amount',
        'status',
        'file_path',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
