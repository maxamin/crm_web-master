<?php

namespace app\models\queries;


use app\models\RelationTypes;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

trait MorphQueryTrait
{
    public function morphMany($class)
    {
        $relationType = RelationTypes::findOne(['class_name' => $class]);

        if ($relationType == null) {
            throw new InvalidParamException($class . ' not found in ' . RelationTypes::tableName() . ' table');
        }

        return $this->andOnCondition([$this->getAliasTableName() . '.relation_type' => $relationType->id]);
    }


    public function morphOne($class, $called)
    {
        /** @var $class ActiveRecord */
        /** @var $called ActiveRecord */

        $ids = $class::find()
            ->join('INNER JOIN', $called::tableName() . ' `called`', '`called`.`relation_id` = ' . $class::tableName() . '.`id`')
            ->join('INNER JOIN',
                RelationTypes::tableName() . ' `rt`',
                '`rt`.`id` = `called`.`relation_type` AND `rt`.`class_name` = "' . addslashes($class::className()) . '"')
            ->select([$class::tableName()  . '.id'])->asArray()->all();

        $ids = ArrayHelper::getColumn($ids, 'id');

        return $this->andOnCondition(['in', $this->getAliasTableName() . '.id', $ids]);
    }
}