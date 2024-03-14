<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RecommendedProduct extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function getList(): Collection
    {
        return self::query()->get();
    }
}
