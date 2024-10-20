<?php

namespace app\models\queries;

use Yii;

use app\framework\db\ActiveQuery;

class LeadsQuery extends ActiveQuery
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

    public function newest()
    {
        return $this->orderBy(['created_at' => SORT_DESC]);
    }

    public function onlyOwnBranch($branch = null)
    {
        if (!isset($branch)) {
            $branch = Yii::$app->user->identity->profile->branch->id;
        }

        return $this->joinWith(['rUser lqRUser' => function ($query) {
            $query->joinWith(['profile lqRProfile']);
        }])->joinWith(['cUser lqCUser' => function ($query) {
            $query->joinWith(['profile lqCProfile']);
        }])->andOnCondition(['or',
            ['lqRProfile.branch_id' => $branch],
            ['lqCProfile.branch_id' => $branch]
        ]);
    }
}