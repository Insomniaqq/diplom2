<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'department_id',
        'quantity',
        'distributed_by',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }
}
