<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    const TYPE_ARTICLE = 1;
    private static $modeName;
    protected $table = 'selection';
    protected $guarded = [];

    public static $model = [
        self::TYPE_ARTICLE => 'Article'
    ];

    /**
     * 设置关联模型名称
     * @param $name
     */
    public static function setModelName($name) {
        self::$modeName = '\\App\\'.$name;
        return new self();
    }

    /**
     * 关联源数据
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function origin() {
        $relation = new self::$modeName();
        return $this->belongsTo($relation, 'originId');
    }
}
