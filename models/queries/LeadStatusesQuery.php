<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;
use app\models\LeadStatuses;

class LeadStatusesQuery extends ActiveQuery
{
    // conditions appended by default (can be skipped)
    public function init()
    {
        parent::init();
    }

    // ... add customized query methods here ...


    /**
     * В работе
     *
     * @return $this
     */
    public function inWork()
    {
        return $this->andOnCondition(['in_work' => LeadStatuses::IN_WORK]);
    }

    /**
     * Сортировка по очереди
     *
     * @return $this
     */
    public function ordered()
    {
        return $this->orderBy(['ord' => SORT_ASC]);
    }
}