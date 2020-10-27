<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blogtag extends Model
{
    protected $fillable = ['blog_id', 'tag_id'];
}
