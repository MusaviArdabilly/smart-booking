<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'desk_id',
        'date',
        'status',
    ];

    public function time()
    {
        return $this->hasOne('App\Models\BookingTime');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function desk()
    {
        return $this->belongsTo('App\Models\Desk');
    }
}
