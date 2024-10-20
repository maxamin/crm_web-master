<?php

namespace app\models\queries;


use app\framework\db\ActiveQuery;

class ProductsQuery extends ActiveQuery
{
    // conditions appended by default (can be skipped)
    public function init()
    {
        parent::init();
    }

    // ... add customized query methods here ...

    /**
     * @param bool $state
     * @return $this
     */
    public function active($state = true)
    {
        return $this->andOnCondition(['active' => $state]);
    }
}