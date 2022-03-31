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
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $incrementing = false;
    const Validation_Account_Rules = [
        'id' => 'required|integer|unique:users,id',
        'name' => 'required|string',
        'birthdate' => 'required|date',
        'phone' => 'required|string|unique:users,phone',
        'email' => 'required|string|unique:users,email',
        'role' => 'required|string|in:Normal,HR,Admin,Accountant',
        'can_wfh'=>'required|boolean',
        'shift_id' => 'required|integer'

    ];
    const Validation_Update_Account_Rules = [
        'name' => 'string',
        'birthdate' => 'date',
        'phone' => 'string|unique:users,phone',
        'email' => 'string|unique:users,email',
        'can_wfh'=>'boolean',
    ];
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'birthdate',
        'shift_id'
    ];

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
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function requests()
    {
        return $this->hasMany(Requestdb::class);
    }
    public function supervised()
    {
        return $this->hasMany(User::class,'supervisor');
    }
    public function supervisor()
    {
        return $this->belongsTo(User::class,'supervisor');
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }


}
