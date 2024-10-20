<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;

class UsersQuery extends ActiveQuery
{
    public function byUserName($sort = SORT_ASC) {

        $this->orderBy([$this->getAliasTableName() . '.username' => $sort]);
    }
}