<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\Branch]].
 *
 * @see \app\models\Branch
 */
class BranchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Branch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Branch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
