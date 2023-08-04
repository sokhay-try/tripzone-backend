<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
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
        $this->perPage = \config('app.default_per_page');
    }


    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

}
