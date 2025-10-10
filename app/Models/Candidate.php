<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'class', 'vision', 'mission', 'image', 'total_votes'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}