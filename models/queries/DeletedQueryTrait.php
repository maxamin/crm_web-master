<?php

namespace app\models\queries;


trait DeletedQueryTrait
{
    /**
     * @param bool $state
     * @return static
     */
    public function deleted($state = false)
    {

        if ($state === true) {
            $this->andOnCondition(['not', [$this->getAliasTableName().'.deleted_at' => null]]);
        } elseif ($state === false) {
            $this->andOnCondition([$this->getAliasTableName().'.deleted_at' => null]);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function withoutDeleted()
    {
        return $this->deleted(false);
    }

    /**
     * @return static
     */
    public function onlyDeleted()
    {
        return $this->deleted(true);
    }

    /**
     * @return static
     */
    public function withDeleted()
    {
        return $this->deleted(null);
    }
}