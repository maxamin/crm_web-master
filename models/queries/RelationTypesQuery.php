<?php

namespace app\models\queries;

use app\framework\db\ActiveQuery;


class RelationTypesQuery extends ActiveQuery
{

    /**
     * @param array $classMap
     * @return $this
     */
    public function byClassMap(array $classMap)
    {
        $condition = ['or'];

        foreach ($classMap as $class) {
            $condition[] = ['=', $this->getAliasTableName().'.class_name', $class];
        }

        return $this->andOnCondition($condition);
    }

    /**
     * @param array $aliases
     * @return $this
     */
    public function byAliases(array $aliases)
    {
        $condition = ['or'];

        foreach ($aliases as $alias) {
            $condition[] = ['=', $this->getAliasTableName() . '.alias', $alias];
        }

        return $this->andOnCondition($condition);
    }
}