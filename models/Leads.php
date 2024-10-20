<?php

namespace app\models;

use app\interfaces\ActiveRecordIdentifier;
use app\models\queries\LeadsQuery;

use Yii;

/**
 * This is the model class for table "leads".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $lead_status_id
 * @property integer $sum
 * @property string $c_user_id
 * @property string $r_user_id
 * @property string $comment
 * @property string $contacts_id
 *
 * @property Contacts $contacts
 * @property Users $cUser
 * @property Users $rUser
 * @property LeadsProducts[] $leadsProducts
 */
class Leads extends \yii\db\ActiveRecord implements ActiveRecordIdentifier
{
    use SoftDeleteTrait;
    use CascadeDeleteTrait;
    use MorphActiveRecordTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leads}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['lead_status_id', 'c_user_id', 'r_user_id', 'contacts_id', 'comment'], 'required'],
            [['sum'], 'filter', 'filter' => function ($value) {
                return preg_replace('/[^0-9]/', '', $value);
            }],
            [['sum'], 'match', 'pattern' => '/^\d{1,3}(,\d{3}|\d)*$/'],
            [['lead_status_id', 'c_user_id', 'r_user_id', 'contacts_id'], 'integer'],
            [['comment'], 'trim'],
            [['comment'], 'string'],
            [['contacts_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['contacts_id' => 'id']],
            [['c_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['c_user_id' => 'id']],
            [['r_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['r_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'created_at' => 'Создана',
            'updated_at' => 'Обновлена',
            'lead_status_id' => 'Статус',
            'sum' => 'Сумма',
            'c_user_id' => 'Создатель',
            'r_user_id' => 'Ответственный',
            'comment' => 'Описание Сделки',
            'contacts_id' => 'Юр.\Физ. лицо',
        ];
    }

    public function cascadeDeleteRelations()
    {
        return [
            'tasks',
            'comments',
            'leadsProducts',
        ];
    }

    /** @inheritdoc */
    public function init()
    {
        if (!($this instanceof LeadsSearch) && $this->isNewRecord) {
            $this->r_user_id = Yii::$app->user->id;
            $this->c_user_id = Yii::$app->user->id;
        }

        parent::init();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        $contact = mb_substr($this->contacts->getType(), 0, -1) . 'а';

        $contact = mb_strtolower($contact);

        return 'Сделка ' . $contact;
    }

    /** @inheritdoc */
    public static function find($deleted = false)
    {
        return (new LeadsQuery(get_called_class()))->deleted($deleted);
    }

    public function link($name, $model, $extraColumns = [])
    {
        if ((($model instanceof Tasks) || ($model instanceof Comments)) && !$model->isRelationPopulated('related')) {
            $model->populateRelation('related', $this);
        }

        return parent::link($name, $model, $extraColumns);
    }

    public function getTasks()
    {
        return $this->morphMany(Tasks::className(), ['relation_id' => 'id']);
    }

    public function getComments()
    {
        return $this->morphMany(Comments::className(), ['relation_id' => 'id']);
    }

    /**
     * @param $status
     * @return bool
     */
    public function changeStatus($status)
    {
        $leadStatus = LeadStatuses::findOne($status);

        if (!$leadStatus) {
            $this->addError('lead_status_id', 'Статус не существует.');
            return false;
        }

        $this->link('leadStatus', $leadStatus);

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'contacts_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'c_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'r_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeadStatus()
    {
        return $this->hasOne(LeadStatuses::className(), ['id' => 'lead_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeadsProducts()
    {
        return $this->hasMany(LeadsProducts::className(), ['leads_id' => 'id']);
    }

    public function getIdentifier()
    {
        if ($this->leadsProducts != null) {

            $products = [];

            foreach ($this->leadsProducts as $product) {
                $products[] = trim($product->product->name);
            }

            $identifier = mb_strtolower(implode(', ', $products));

            $identifier = mb_strtoupper(mb_substr($identifier, 0, 1)) . mb_substr($identifier, 1);
        } else {

            $identifier = 'Сделка #' . $this->id;
        }

        return $identifier;
    }
}
