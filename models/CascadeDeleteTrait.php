<?php


namespace app\models;

use yii\db\ActiveRecord;

trait CascadeDeleteTrait
{
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if (!method_exists($this, 'cascadeDeleteRelations')) {
            throw new \yii\base\InvalidConfigException(sprintf('`%s` должен иметь метод `%s`.', get_class($this), 'cascadeDeleteRelations'));
        }

        $relations = $this->cascadeDeleteRelations();

        if (!is_array($relations)) {
            throw new \yii\base\InvalidConfigException(sprintf('Метод `%s` класса `%s` должен возвращать массив.', 'cascadeDeleteRelations', get_class($this)));
        }

        foreach ($relations as $relation) {

            $method = 'get' . ucfirst($relation);

            if (method_exists($this, $method)) {

                foreach ($this->{$relation} as $item) {
                    /** @var $item ActiveRecord */

                    if ($item->delete() == false) {

                        return false;
                    }
                }

            } else {
                throw new \yii\base\InvalidConfigException(sprintf('`%s` должен иметь метод `%s`.', get_class($this), $method));
            }
        }

        return true;
    }

    public function transactions()
    {
        return [
            $this->getScenario() => self::OP_DELETE,
        ];
    }
}