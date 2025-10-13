<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'content'
    ];

    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }
}
