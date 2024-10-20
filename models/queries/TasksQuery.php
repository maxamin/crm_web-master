<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;
use Yii;


class TasksQuery extends ActiveQuery
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

    public function active($state = true)
    {
        return $this->andOnCondition(['active' => $state]);
    }

    public function onlyOwnBranch($branch = null)
    {
        if (!isset($branch)) {
            $branch = Yii::$app->user->identity->profile->branch->id;
        }

        return $this->joinWith(['user tqRUser' => function ($query) {
            $query->joinWith(['profile tqRProfile']);
        }])->joinWith(['cUser tqCUser' => function ($query) {
            $query->joinWith(['profile tqCProfile']);
        }])->andOnCondition(['or',
            ['tqRProfile.branch_id' => $branch],
            ['tqCProfile.branch_id' => $branch]
        ]);
    }
}