<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operate extends Model
{
    const TYPE_ARTICLE_USE = 1;
    protected $table = 'operate';
    protected $guarded = [];

    public static $type = [
        'article_use' => self::TYPE_ARTICLE_USE
    ];
}
