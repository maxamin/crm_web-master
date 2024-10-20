<?php

namespace app\models;

use app\models\queries\TasksQuery;
use Yii;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "tasks".
 *
 * @property string $id
 * @property string $created_at
 * @property string $closed_at
 * @property string $updated_at
 * @property string $day
 * @property string $hour
 * @property string $task_type_id
 * @property string $relation_type
 * @property string $relation_id
 * @property string $comment
 * @property string $close_comment
 * @property integer $status
 * @property integer $user_id
 * @property integer $close_user_id
 */
class Tasks extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;
    use CascadeDeleteTrait;
    use MorphActiveRecordTrait;

    const SCENARIO_CLOSE = 'close';

    const STATUS_OPENED = 0;
    const STATUS_CLOSED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tasks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'day', 'hour'], 'safe'],
            [['day', 'hour', 'task_type_id', 'relation_type', 'relation_id', 'status', 'user_id'], 'required'],
            [['task_type_id', 'relation_type', 'relation_id', 'user_id', 'close_user_id', 'c_user_id', 'status'], 'integer'],
            [['comment', 'close_comment'], 'string'],
            [['close_comment'], 'required', 'on' => self::SCENARIO_CLOSE],
            [['comment', 'close_comment'], 'trim'],
            [['day'], 'filter', 'filter' => function ($value) {

                return !empty($value) ? Yii::$app->formatter->asDate($value, 'php:Y-m-d') : null;
            }],
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
            'day' => 'Дата',
            'hour' => 'Время',
            'task_type_id' => 'Тип задачи',
            'relation_type' => 'Тип отношения',
            'relation_id' => 'Отношение',
            'c_user_id' => 'Создатель',
            'close_user_id'=> 'Закрыл',
            'comment' => 'Описание',
            'close_comment' => 'Результат',
            'status' => 'Статус',
            'dateTime' => 'Дата/Время',
        ];
    }

    public function cascadeDeleteRelations()
    {
        return [
            'comments',
        ];
    }

    public static function morphMap()
    {
        return [
            Legal::className(),
            Natural::className(),
            Leads::className(),
        ];
    }

    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_CLOSE => ['close_comment'],
        ];

        return array_merge(parent::scenarios(), $scenarios);
    }

    /**
     * @return bool
     */
    public function init()
    {
        if (!($this instanceof TasksSearch) && $this->isNewRecord) {
            $this->user_id = Yii::$app->user->id;
            $this->c_user_id = Yii::$app->user->id;
            $this->status = self::STATUS_OPENED;
        }

        return parent::init();
    }

    /**
     * @param bool $deleted
     * @return TasksQuery
     */
    public static function find($deleted = false)
    {
        return (new TasksQuery(get_called_class()))->deleted($deleted);
    }

    public function beforeSave($insert)
    {
        if ($this->isRelationPopulated('related')) {
            $this->relation_type = RelationTypes::findOne(['class_name' => (new \ReflectionClass($this->related))->getName()])->id;
            $this->relation_id = $this->related->id;
        }

        if ($this->isAttributeChanged('status')) {
            switch ($this->status) {
                case self::STATUS_OPENED:
                    $this->close_user_id = null;
                    break;
                case self::STATUS_CLOSED:
                    $this->close_user_id = Yii::$app->user->id;
                    $this->closed_at = new Expression('NOW()');
                    break;
            }
        }

        return parent::beforeSave($insert);
    }

    public function link($name, $model, $extraColumns = [])
    {
        if (($model instanceof Comments) && !$model->isRelationPopulated('related')) {
            $model->populateRelation('related', $this);
        }

        return parent::link($name, $model, $extraColumns);
    }

    /**
     * @param ActiveRecord $model
     * @return $this
     */
    public function relatedWith(ActiveRecord $model)
    {
        $className = (new \ReflectionClass($model))
            ->getName();

        if (!in_array($className, static::morphMap())) {
            throw new InvalidParamException('Tasks can\'t be related with ' . $className);
        }

        $relationType = RelationTypes::findOne(['class_name' => $className]);

        if ($relationType == null) {
            throw new InvalidParamException($className . ' not found in ' . RelationTypes::tableName() . ' table');
        }

        $this->relation_type = $relationType->id;
        $this->relation_id = $model->id;

        return $this;
    }


    /**
     * Change status to closed
     *
     * @return bool
     */
    public function close($close_comment)
    {
        $this->scenario = self::SCENARIO_CLOSE;
        $this->close_comment = $close_comment;
        $this->status = self::STATUS_CLOSED;

        return $this->save();
    }

    public function getRelated()
    {
        return isset($this->relationType) ? $this->hasOne($this->relationType->class_name, ['id' => 'relation_id'])
            : null;
    }

    /**
     * Возвращает true если задача просрочена, false в противном случае
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->isOpen() && $this->isOutOfDate();
    }

    /**
     * Возвращает true если задача открыта, false в противном случае
     *
     * @return bool
     */
    public function isOpen()
    {
        return isset($this->status) ? $this->status === self::STATUS_OPENED : false;
    }


    /**
     * Возвращает true если дата\время задачи меньше заданого либо текущего, false в противном случае
     *
     * @param null $unixTime
     * @return bool
     */
    public function isOutOfDate($unixTime = null)
    {
        $unixTime = isset($unixTime) ? $unixTime : time();

        return isset($this->dateTime) ? strtotime($this->dateTime) < $unixTime : false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->morphMany(Comments::className(), ['relation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegals()
    {
        return $this->morphOne(Legal::className(), ['id' => 'relation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNaturals()
    {
        return $this->morphOne(Natural::className(), ['id' => 'relation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeads()
    {
        return $this->morphOne(Leads::className(), ['id' => 'relation_id']);
    }

    public function getType()
    {
        return $this->hasOne(TasksTypes::className(), ['id' => 'task_type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getCUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'c_user_id']);
    }

    public function getCloseUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'close_user_id']);
    }

    public function getRelationType()
    {
        return $this->hasOne(RelationTypes::className(), ['id' => 'relation_type']);
    }

    public function getDateTime()
    {
        return $this->day . ' ' . $this->hour;
    }
}
