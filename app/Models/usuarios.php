<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres', 'apellidos', 'email',
    ];

    protected $casts = [
        'email' => 'email',
    ];

    public function hasEmail(){
        return ! is_null($this->email);
    }
}
