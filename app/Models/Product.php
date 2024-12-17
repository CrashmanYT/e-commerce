<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        "name",
        "slug",
        "description",
        "price",
        "size",
        "weight",
        "stock",
        "photo"
    ];
    public function getRouteKeyName(): string 
    {
        return "slug";
    }
}
