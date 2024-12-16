<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'type_use',
        'amount_type',
        'amount',
        'expired_date',
        'active',
        'especify_courses',
        'especify_users',
        'flg_used',
        'used_date_time',
    ];

    public function entityCoupons()
    {
        return $this->hasMany(EntityCoupon::class, 'coupon_id');
    }

    public function coursesCoupons()
    {
        return $this->morphedByMany(Course::class, 'couponable', 'entity_coupons')
            ->withTimestamps();
    }

    public function usersCoupons()
    {
        return $this->morphedByMany(User::class, 'couponable', 'entity_coupons')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_id');
    }

    public function advertisements()
    {
        return $this->hasOne(Advertising::class, 'coupon_id');
    }
}
