<?php

namespace App\Models;

use App\Models\PlaceCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Config;

class Category extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];
    protected $perPage;

    public function __construct()
    {
        $this->perPage = config('app.default_per_page');
    }

    public function placeCategories(): BelongsToMany
    {
        return $this->belongsToMany(PlaceCategory::class);
    }
}
