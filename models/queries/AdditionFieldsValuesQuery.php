<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;


class AdditionFieldsValuesQuery extends ActiveQuery
{
    use DeletedQueryTrait;
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
}