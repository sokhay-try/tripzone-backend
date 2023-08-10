<?php

namespace App\Models;

use App\Models\Place;
use App\Models\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'place_id',
        'user_id',
        'rating',
        'review_text'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

}
