<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityCoupon extends Model
{
    use HasFactory;

    protected $table = 'entity_coupons';

    protected $fillable = [
        'coupon_id',
        'couponable_type',
        'couponable_id',
        'flg_used',
        'used_date_time',
    ];

    public function couponable()
    {
        return $this->morphTo('couponable');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'couponable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'couponable_id');
    }
}
