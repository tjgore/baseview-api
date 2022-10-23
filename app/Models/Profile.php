<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    const GENDER = ['Male', 'Female', 'Neither'];

    protected $guarded = ['user_id'];  

    protected $casts = [
        'general' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
