<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $fillable=['company_name','branches','specifity','company_email','company_phone','location','country','photo','latiude','longtiude','distance'];
    const cofigRequestValidation=['company_name'=> 'required|string','branches'=> 'required|string','specifity'=> 'required|string','company_email'=> 'required|string','company_phone'=> 'required|string','location'=> 'required|string','country'=> 'required|string','photo'=> 'required','latiude'=> 'required|string','longtiude'=> 'required|string','distance'=> 'required|string'];
}
