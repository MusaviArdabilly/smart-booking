<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->hasMany('App\Models\Booking');
    }

    public function bookingMonth()
    {
        return $this->hasMany('App\Models\Booking')->whereMonth('created_at', Carbon::now()->month);
    }

    public function assessment()
    {
        return $this->hasMany('App\Models\Assessment');
    }

    public function assessmentMonth()
    {
        return $this->hasMany('App\Models\Assessment')->whereMonth('created_at', Carbon::now()->month);
    }

    public function assessmentLog()
    {
        return $this->hasManyThrough('App\Models\AssessmentLog', 'App\Models\Assessment', null, 'assessment_id');
    }

    public function assessmentLogMonth()
    {
        return $this->hasManyThrough('App\Models\AssessmentLog', 'App\Models\Assessment', null, 'assessment_id')
            ->whereMonth('assessment_logs.created_at', Carbon::now()->month);
    }

    public function notification()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(1000)
            ->height(1000);
    }
}
