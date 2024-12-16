<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'status',
        'order_date',
        'payment_type',
        'coupon_id',
        'new',
        'uuid_transaction'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function productCourses()
    {
        return $this->morphedByMany(Course::class, 'orderable', 'order_details')
            ->withPivot(['quantity', 'unit_price'])->withTimestamps();
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
