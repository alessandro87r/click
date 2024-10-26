<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RatingRestaurant;

class Restaurant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    public function rating()
    {
        return $this->hasMany(RatingRestaurant::class);
    }
}
