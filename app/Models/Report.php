<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'title',
        'description',
        'filepath',
        'alarm_id',
        'alarm_type',
        'client',
        'status',
        'classification',
        'comments',
        'evidence',
    ];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
