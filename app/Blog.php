<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'post',
        'postExcerpt',
        'slug',
        'user_id',
        'featuredImage',
        'metaDescription',
        'views'
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['slug'] = Str::slug($title); //Converts title to slug
    }
}
