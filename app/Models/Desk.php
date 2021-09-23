<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sector_id',
        'name',
        'description',
        'status',
    ];

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
    }

    public function booking()
    {
        return $this->hasMany('App\Models\Booking');
    }
}
