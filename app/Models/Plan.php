<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'course_id',
        'duration_type',
        'duration',
        'flg_recom'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function cart()
    {
        return $this->morphMany(ShoppingCart::class, 'buyable');
    }

    public function item()
    {
        return $this->morphOne(OrderDetail::class, 'orderable');
    }

    public function subscription()
    {
        return $this->morphMany(Subscription::class, 'subscriptable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function advertisements()
    {
        return $this->hasMany(Advertising::class, 'plan_id');
    }
}
