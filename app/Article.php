<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function star() {
        return $this->hasOne(ArticleStar::class, 'articleId');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'article_tags', 'articleId', 'tagId')->withTimestamps();
    }

    public function getCreatedAtAttribute($date)
    {
        Carbon::setLocale('zh');
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }

    public function getContentAttribute($content)
    {
        return htmlspecialchars_decode($content);
    }

    public function getIsAddUseAttribute() {
        return $this->attributes['isAddUse'] = is_null(Operate::where(['originId'=>$this->id, 'userId'=>\Auth::id(), 'type'=>Operate::TYPE_ARTICLE_USE, 'state'=>1])->first()) ? 0 : 1;
    }

    public function getJumpLinkAttribute() {
        return $this->attributes['jumpLink'] = '/article/'.$this->id;
    }
}
