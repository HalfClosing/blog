<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleStar extends Model
{
    protected $guarded = [];

    public function article()
    {
        return $this->hasMany(Article::class, 'articleId');
    }
}
