<?php

namespace app\models;

use app\models\queries\LeadsProductsQuery;
use Yii;

/**
 * This is the model class for table "leads_products".
 *
 * @property integer $id
 * @property string $product_id
 * @property string $leads_id
 *
 * @property Products $product
 * @property Leads $leads
 * @property LeadsProductStatuses $leadsProductStatus
 */
class LeadsProducts extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leads_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'leads_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['leads_id'], 'exist', 'skipOnError' => true, 'targetClass' => Leads::className(), 'targetAttribute' => ['leads_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'leads_id' => 'Leads ID',
        ];
    }

    public static function find($deleted = false)
    {
        return (new LeadsProductsQuery(get_called_class()))->deleted($deleted);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeads()
    {
        return $this->hasOne(Leads::className(), ['id' => 'leads_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeadsProductStatus()
    {
        return $this->hasOne(LeadsProductStatuses::className(), ['id' => 'status']);
    }
}
