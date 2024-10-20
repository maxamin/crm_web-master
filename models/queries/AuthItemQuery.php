<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\AuthItem]].
 *
 * @see \app\models\AuthItem
 */
class AuthItemQuery extends \yii\db\ActiveQuery
{
    const ROLES = 1;
    const PERMISSIONS = 2;


    /**
     * @return $this
     */
    public function onlyPermissions()
    {
        return $this->andOnCondition(['type' => self::PERMISSIONS]);
    }

    /**
     * @return $this
     */
    public function onlyRoles()
    {
        return $this->andOnCondition(['type' => self::ROLES]);
    }
}
