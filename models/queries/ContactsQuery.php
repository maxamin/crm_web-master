<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;
use app\models\Contacts;
use Yii;

class ContactsQuery extends ActiveQuery
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
        $this->orderBy([$this->getAliasTableName().'.created_at' => SORT_DESC]);

        if (isset($count)) {
            $this->limit($count);
        }

        return $this;
    }

    public function active($state = true)
    {
        return $this->andOnCondition([$this->getAliasTableName().'.active' => $state]);
    }

    public function onlyOwnBranch($branch = null)
    {
        if (!isset($branch)) {
            $branch = Yii::$app->user->identity->profile->branch->id;
        }

        return $this->joinWith(['rUser cqRUser' => function ($query) {
            $query->joinWith(['profile cqRProfile']);
        }])->joinWith(['cUser cqCUser' => function ($query) {
            $query->joinWith(['profile cqCProfile']);
        }])->andOnCondition(['or',
            ['cqRProfile.branch_id' => $branch],
            ['cqCProfile.branch_id' => $branch]
        ]);
    }

    public function onlyLegal()
    {
        $this->andOnCondition([$this->getAliasTableName().'.type' => Contacts::TYPE_LEGAL]);
    }

    public function onlyNatural()
    {
        $this->andOnCondition([$this->getAliasTableName().'.type' => Contacts::TYPE_NATURAL]);
    }
}