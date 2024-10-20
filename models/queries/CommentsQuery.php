<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;

use app\models\RelationTypes;

class CommentsQuery extends ActiveQuery
{
    use DeletedQueryTrait;
    use MorphQueryTrait;
    // conditions appended by default (can be skipped)
    public function init()
    {
        parent::init();
    }

    public function prepare($builder)
    {

        return parent::prepare($builder);
    }

    // ... add customized query methods here ...


    public function last($count = null)
    {
        $this->orderBy(['created_at' => SORT_DESC]);

        if (isset($count)) {
            $this->limit($count);
        }

        return $this;
    }

    public function whereRelatedWith($model)
    {
        return $this->andOnCondition(['and',
                ['relation_id' => $model->id],
                ['relation_type' => RelationTypes::findOne(['class_name' => (new \ReflectionClass($model))->getName()])->id]
            ]);
    }
}