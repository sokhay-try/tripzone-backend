<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
