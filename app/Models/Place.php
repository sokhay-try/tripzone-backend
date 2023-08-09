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

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    // this is the recommended way for declaring event handlers
    public static function boot() {
        parent::boot();
        self::deleting(function($place) { // before delete() method call this
            $place->images()->each(function($image) {
                // get filename image
                $pathinfo = pathinfo($image->url);
                $fileName = $pathinfo['filename'].'.'.$pathinfo['extension'];
                if(\File::exists(public_path('images/'.$fileName))){
                    \File::delete(public_path('images/'.$fileName));
                }
                $image->delete(); // <-- direct deletion
            });
             // do the rest of the cleanup...
        });
    }

}
