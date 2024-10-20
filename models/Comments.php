<?php

namespace app\models;

use Yii;

use app\models\queries\CommentsQuery;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "comments".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $body
 * @property string $c_user_id
 * @property string $relation_type
 * @property string $relation_id
 *
 * @property Users $cUser
 */
class Comments extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;
    use MorphActiveRecordTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['body', 'c_user_id', 'relation_type', 'relation_id'], 'required'],
            [['body'], 'string'],
            [['c_user_id', 'relation_type', 'relation_id'], 'integer'],
            [['c_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['c_user_id' => 'id']],
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
            'body' => 'Заметка',
            'c_user_id' => 'Создатель',
            'relation_type' => 'Отношение',
            'relation_id' => 'Тип отношения',
        ];
    }

    /**
     * @return CommentsQuery
     */
    public static function find($deleted = false)
    {
        return (new CommentsQuery(get_called_class()))->deleted($deleted);
    }

    /**
     * @return bool
     */
    public function init()
    {
        if ($this->isNewRecord) {
            $this->c_user_id = Yii::$app->user->id;
        }

        parent::init();
    }

    public function beforeSave($insert)
    {
        if ($this->isRelationPopulated('related')) {
            $this->relation_type = RelationTypes::findOne(['class_name' => (new \ReflectionClass($this->related))->getName()])->id;
            $this->relation_id = $this->related->id;
        }

        return parent::beforeSave($insert);
    }

    /**
     * Возвращает модель - собственник заметки
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getRelated()
    {
        return isset($this->relation_type) ? $this->hasOne($this->relationType->class_name, ['id' => 'relation_id']) : new ActiveQuery(null);
    }

    /**
     * Возвращает создателя заметки
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'c_user_id']);
    }
}
