<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    use HasFactory;

    protected $table = 'advertisements';

    protected $fillable = [
        'text',
        'coupon_id',
        'plan_id',
        'active'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
