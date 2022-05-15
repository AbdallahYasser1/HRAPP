<?php

namespace App\Models;


use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
use App\Models\Absence;
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
        'shift_id',
        'supervisor'
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
    public function password(): Attribute
    {
        return Attribute::make(
        set: fn ($value) => Hash::make($value)
        );
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
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
    public function vacationday(){
        return $this->hasOne(Vacationday::class);
    }

// Salary
public function salaryTerm() {
       return $this->hasOne(SalaryTerm::class);
}

public function salarySlips() {
        return $this->hasMany(SalarySlip::class);
}

public function lastSlip() {
        return $this->hasOne(SalarySlip::class)->latest();
}

public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function absences()
{
    return $this->hasMany(Absence::class);
}
}
