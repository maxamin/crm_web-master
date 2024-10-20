<?php

namespace app\models;

use app\interfaces\ActiveRecordIdentifier;
use app\models\queries\ContactsQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "contacts".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $okpo
 * @property string $website
 * @property string $address
 * @property string $description
 * @property integer $active
 * @property integer $cities_id
 * @property integer $c_user_id
 * @property integer $r_user_id
 * @property integer $type
 * @property string $deleted_at
 * @property integer $import_id
 *
 * @property Cities $cities
 * @property Users $cUser
 * @property Users $rUser
 * @property ContactsInfos[] $contactsInfos
 * @property ContactsLink[] $contactsLinks
 * @property ContactsLink[] $contactsLinks0
 * @property Leads[] $leads
 */
class Contacts extends ActiveRecord implements ActiveRecordIdentifier
{
    use SoftDeleteTrait;
    use CascadeDeleteTrait;
    use MorphActiveRecordTrait;

    const SCENARIO_IMPORT = 'import';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const TYPE_NATURAL = 1;
    const TYPE_LEGAL = 2;

    private $extraAttributes = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_IMPORT => ['name', 'okpo', 'website', 'address', 'active', 'cities_id', 'description',
                'r_user_id', 'import_id'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'active', 'cities_id', 'c_user_id', 'r_user_id', 'type'], 'required'],
            [['description'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['import_id'], 'required', 'on' => self::SCENARIO_IMPORT],
            [['cities_id', 'c_user_id', 'r_user_id', 'type', 'import_id'], 'integer'],
            [['active'], 'boolean'],
            [['okpo'], 'integer'],
            [['okpo'], 'string', 'length' => [8, 12]],
            [['okpo'], 'unique'],
            [['okpo'], 'default', 'value' => null],
            [['name', 'address', 'description'], 'trim'],
            [['description'], 'string'],
            [['name', 'address'], 'string', 'length' => [3, 255]],
            [['website'], 'url', 'defaultScheme' => 'http'],
            [['cities_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(),
                'targetAttribute' => ['cities_id' => 'id']],
            [['c_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(),
                'targetAttribute' => ['c_user_id' => 'id']],
            [['r_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(),
                'targetAttribute' => ['r_user_id' => 'id']],
            [['import_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContactsImport::className(),
                'targetAttribute' => ['import_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'name' => 'Имя/Название',
            'okpo' => 'ИНН/ОКПО',
            'website' => 'Веб-сайт',
            'address' => 'Адрес',
            'description' => 'Описание Клиента',
            'active' => 'Активный',
            'cities_id' => 'Город',
            'c_user_id' => 'Создатель',
            'r_user_id' => 'Ответственный',
            'type' => 'Тип',
            'deleted_at' => 'Удален',
            'import_id' => '# импорта',
        ];
    }

    public function cascadeDeleteRelations()
    {
        return [
            'tasks',
            'leads',
            'comments',
            'contactsInfos',
            'contactsLinks',
            'contactsLinks0',
            'additionalValues',
        ];
    }

    /**
     * @return bool
     */
    public function init()
    {
        if (!($this instanceof ContactsSearch) && $this->isNewRecord) {
            $this->r_user_id = Yii::$app->user->id;
            $this->c_user_id = Yii::$app->user->id;
            $this->active = self::STATUS_ACTIVE;
        }

        return parent::init();
    }

    /**
     * @return array
     */
    public static function typeLabels()
    {
        return [
            self::TYPE_LEGAL => 'legal',
            self::TYPE_NATURAL => 'natural',
        ];
    }

    public static function types()
    {
        return [
            self::TYPE_LEGAL => 'Юр.лицо',
            self::TYPE_NATURAL => 'Физ.лицо',
        ];
    }

    /**
     * @return mixed
     */
    public function getTypeLabel()
    {
        return (static::typeLabels())[$this->type];
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return (static::types())[$this->type];
    }

    /** @inheritdoc */
    public function link($name, $model, $extraColumns = [])
    {
        if ((($model instanceof Tasks) || ($model instanceof Comments)) && !$model->isRelationPopulated('related')) {
            $model->populateRelation('related', $this);
        }

        return parent::link($name, $model, $extraColumns);
    }

    /**
     * @inheritdoc
     */
    public static function find($deleted = false)
    {
        return (new ContactsQuery(get_called_class()))->deleted($deleted);
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->morphMany(Tasks::className(), ['relation_id' => 'id']);
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->morphMany(Comments::className(), ['relation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasOne(Cities::className(), ['id' => 'cities_id']);
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
    public function getContactsInfos()
    {
        return $this->hasMany(ContactsInfos::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactsLinks()
    {
        return $this->hasMany(ContactsLink::className(), ['link_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactsLinks0()
    {
        return $this->hasMany(ContactsLink::className(), ['contacts_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalValues()
    {
        return AdditionFieldsValues::find()
            ->innerJoinWith('additionField', ['addition_fields.id=addition_fields_values.addition_field_id'])
            ->where(['addition_fields_values.foregin_table_id' => $this->id, 'addition_fields.type' => $this->type])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeads()
    {
        return $this->hasMany(Leads::className(), ['contacts_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactsImport()
    {
        return $this->hasOne(ContactsImport::className(), ['id' => 'import_id']);
    }

    public function getExtraAttributes()
    {
        if(!isset($this->extraAttributes)) {
            foreach ($this->getAdditionalValues() as $key => $value) {
                $this->extraAttributes[$value->additionField->name] = $value->additionField->is_directory ?
                    AdditionalValuesDirectories::find()
                        ->where(['addition_fields_id' => $value->additionField->id, 'value' => $value->value])
                        ->one()->name
                    : $value->value;
            }
        }

        return $this->extraAttributes;
    }

    public function getIdentifier()
    {
        return $this->name;
    }
}
