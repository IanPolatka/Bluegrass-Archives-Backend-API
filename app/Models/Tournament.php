<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year_id'
    ];

    public function school_year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
}
