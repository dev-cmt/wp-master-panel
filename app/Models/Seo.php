<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $fillable = [
        'meta_title', 'meta_description', 'meta_keywords',
        'canonical_url', 'og_title', 'og_description',
        'og_image', 'twitter_card', 'robots'
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}
