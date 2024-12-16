<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = "subscriptions";

    protected $fillable = [
        'user_id',
        'order_id',
        'subscriptable_type',
        'subscriptable_id',
        'auto_renewal',
        'status',
        'start_date',
        'end_date',
        'certification_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscriptable()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function productCertification()
    {
        return $this->belongsTo(ProductCertification::class, 'certification_id');
    }
}
