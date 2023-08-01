<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Review;
use App\Models\PlaceCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Place extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'visitor',
        'distance',
        'status',
        'address',
        'province_id',
        'created_by',
        'posted_at',
        'created_at',
        'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class);
    }

    public function placeCategories(): BelongsToMany
    {
        return $this->belongsToMany(PlaceCategory::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
