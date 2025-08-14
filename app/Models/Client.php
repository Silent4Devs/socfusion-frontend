<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'emails',
        'phone',
        'address',
        'logo',
    ];

    protected $table = 'clients';

    protected $casts = [
        'emails' => 'array', 
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
