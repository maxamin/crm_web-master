<?php

namespace app\models;

use app\models\queries\ProductsQuery;

/**
 * This is the model class for table "products".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property integer $active
 *
 * @property Leads[] $leads
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'active'], 'required'],
            [['active'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Name',
            'active' => 'Active',
        ];
    }

    /**
     * @return ProductsQuery
     */
    public static function find()
    {
        return new ProductsQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeads()
    {
        return $this->hasMany(Leads::className(), ['products_id' => 'id']);
    }
}
