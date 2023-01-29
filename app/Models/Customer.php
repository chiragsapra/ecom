<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'jobTitle',
        'email',
        'name',
        'regSince',
        'phone',
    ];
    public $timestamps = FALSE;
}
