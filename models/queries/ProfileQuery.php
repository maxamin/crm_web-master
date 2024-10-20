<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;

class ProfileQuery extends ActiveQuery
{
    public function byName($sort = SORT_ASC) {

        $this->orderBy([$this->getAliasTableName() . '.name' => $sort]);
    }
}