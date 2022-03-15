<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $incrementing = false;
Const Validation_Rules=[
    'id'=>'required|integer|unique:users,id',
    'name' => 'required|string',
    'birthdate' => 'required|date',
    'phone' => 'required|string|unique:users,phone',
    'email' => 'required|string|unique:users,email',
    'role'=>'required|string|in:Normal,HR,Admin,Accountant',
    'shift_id'=>'required|integer'
];
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'birthdate',
    'shift_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
}
