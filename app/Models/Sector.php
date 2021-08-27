<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'floor_id',
        'name',
        'description',
    ];

    public function floor()
    {
        return $this->belongsTo('App\Models\Floor');
    }

    public function desks()
    {
        return $this->hasMany('App\Models\Desk');
    }
}
