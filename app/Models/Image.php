<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['url', 'position'];

    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL for the image.
     *
     * @return string
     */
    public function getUrlAttribute($value)
    {
        return asset('storage/' . $value);
    }
}