<?php
/**
 * Created by PhpStorm.
 * User: maxch
 * Date: 30.03.17
 * Time: 12:02
 */

namespace app\models;


trait MorphActiveRecordTrait
{
    protected function morphMany($class, $link, $with = null)
    {
        return $this->hasMany($class, $link)
            ->morphMany($with ?? static::className());
    }

    protected function morphOne($class, $link, $with = null)
    {
        return $this->hasOne($class, $link)
            ->morphOne($class, $with ?? static::className());
    }
}