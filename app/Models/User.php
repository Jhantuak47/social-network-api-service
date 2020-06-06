<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // authantication rule
    public static function rules($scenario)
    {
        switch($scenario)
        {
            case 'login':
                $rules = [
                    'email' => ['required'],
                    'password' => ['required'],
                ];
                break;
            case 'register':
                $rules = [
                    'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z_ \-\'\.\,]+$/'],
                    'email' => ['required', 'nullable', 'string', 'email', 'max:255'],
                    'password' => ['required', 'string', 'min:6', 'confirmed'],
                    'password_confirmation' => ['required'],
                ];
                break;
            default :
                $rules = [
                    'email' => ['string', 'nullable', 'email', 'required'],
                    'name' => ['string', 'required', 'regex:/^[A-Za-z_ \-\'\.\,]+$/'],
                    'password' => ['required', 'string', 'min:6'],
                ];
        }
        return $rules;
    }

    public function friends()
    {
        return $this->belongsToMany('App\Models\User', 'friends', 'user1', 'user2')
        ->using('App\Models\Friend')
        ->withPivot([
            'status',
        ])->withTimestamps();
    }
}
