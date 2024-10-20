<?php

namespace app\framework\db;

class ActiveQuery extends \yii\db\ActiveQuery
{

    /**
     * Returns the table name and the table alias for [[modelClass]].
     * @param ActiveQuery $query
     * @return array the table name and the table alias.
     */
    private function getQueryTableName($query)
    {
        if (empty($query->from)) {
            /* @var $modelClass ActiveRecord */
            $modelClass = $query->modelClass;
            $tableName = $modelClass::tableName();
        } else {
            $tableName = '';
            foreach ($query->from as $alias => $tableName) {
                if (is_string($alias)) {
                    return [$tableName, $alias];
                } else {
                    break;
                }
            }
        }

        if (preg_match('/^(.*?)\s+({{\w+}}|\w+)$/', $tableName, $matches)) {
            $alias = $matches[2];
        } else {
            $alias = $tableName;
        }

        return [$tableName, $alias];
    }

    /**
     * Returns the table alias (or the table name if empty) of the current ActiveQuery
     * @return string table alias or table name of the current activeQuery
     */
    public function getAliasTableName()
    {
        list (, $alias) = self::getQueryTableName($this);

        return $alias;
    }
}