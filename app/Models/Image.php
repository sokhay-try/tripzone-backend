<?php

namespace App\Models;

use App\Models\Place;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'place_id',
    ];

    protected $perPage;

    public function __construct()
    {
        $this->perPage = config('app.default_per_page');
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

}
