<?php

namespace app\models;

use app\framework\db\ActiveQuery;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * TasksSearch represents the model behind the search form about `app\models\Tasks`.
 */
class TasksSearch extends Model
{
    const STATUS_OPENED = 'opened';
    const STATUS_CLOSED = 'closed';

    const OWN_CREATOR = 'creator';
    const OWN_RESPONSIBLE = 'responsible';

    const TODAY = 'today';
    const TOMORROW = 'tomorrow';
    const NEXT7 = 'next7';
    const ALL = 'all';

    const RELATED_LEGALS = 'legals';
    const RELATED_NATURALS = 'naturals';
    const RELATED_LEGAL_LEADS = 'leads-legals';
    const RELATED_NATURAL_LEADS = 'leads-naturals';

    /** @var string */
    public $q;

    /** @var string */
    public $creator;

    /** @var string */
    public $responsible;

    /** @var string */
    public $closed;

    /** @var string */
    public $status;

    /** @var string */
    public $type;

    /** @var integer */
    public $tpid;

    /** @var string */
    public $related;

    /** @var string */
    public $relid;

    /** @var string */
    public $reltype;

    /** @var integer */
    public $reltid;

    /** @var string */
    public $branch;

    /** @var integer */
    public $rid;

    /** @var integer */
    public $cid;

    /** @var integer */
    public $clid;

    /** @var string */
    public $own;

    /** @var string */
    public $createdAt;

    /** @var string */
    public $from;

    /** @var string */
    public $to;

    /** @var string */
    public $fday;

    /** @var string */
    public $tday;

    /** @var string */
    public $day;


    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q', 'creator', 'responsible', 'closed', 'status', 'branch', 'own', 'createdAt', 'from', 'to',
                'day', 'type', 'fday', 'tday', 'related', 'reltype'], 'string'],
            [['cid', 'rid', 'clid', 'tpid', 'reltid'], 'integer'],
            [['from', 'to', 'fday', 'tday'], 'filter', 'filter' => function ($value) {

                return !empty($value) ? Yii::$app->formatter->asDate($value, 'php:Y-m-d') : null;
            }],
            [['day'], 'default', 'value' => function () {

                return self::TODAY;
            }],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider|ActiveRecord[]
     */
    public function search($params)
    {
        $query = Tasks::find();

        $query->joinWith(['leads.leadsProducts.product']);
        $query->joinWith(['leads.contacts']);

        $query->select([
            new Expression('CONVERT(GROUP_CONCAT(DISTINCT `products`.`name` ORDER BY `products`.`name` SEPARATOR ", " ) USING "utf8") AS `products`'),
            'tasks.*',
            'contacts.type as lct',
        ]);

        $query->groupBy('tasks.id');

        $query = Tasks::find()->from(['tasks' => $query]);

        $query->joinWith('legals');
        $query->joinWith('naturals');

        $query->select([
            new Expression('CONVERT(CONCAT_WS("", `natural`.`name`, `legal`.`name`, `tasks`.`products`) USING "utf8") AS `rel_name`'),
            'natural.type AS nt',
            'legal.type AS lt',
            'tasks.*'
        ]);

        $query = Tasks::find()->from(['tasks' => $query]);

        if (!Yii::$app->user->can('manageAllTasks')) {
            $query->onlyOwnBranch();
        }

        $query->joinWith(['cUser creator' => function ($q) {
            $q->joinWith('profile cProfile');
        }]);

        $query->joinWith(['user responsible' => function ($q) {
            $q->joinWith(['profile rProfile' => function ($q) {
                $q->joinWith(['branch rBranch']);
            }]);
        }]);

        $query->joinWith(['closeUser closed' => function ($q) {
            $q->joinWith('profile clProfile');
        }]);

        $query->joinWith('type');
        $query->joinWith('relationType');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'type' => [
                    'asc' => ['tasks_types.name' => SORT_ASC],
                    'desc' => ['tasks_types.name' => SORT_DESC],
                ],
                'status',
                'dateTime' => [
                    'asc' => ['day' => SORT_ASC, 'hour' => SORT_ASC],
                    'desc' => ['day' => SORT_DESC, 'hour' => SORT_DESC],
                ],
                'reltype' => [
                    'asc' => ['relation_types.name' => SORT_ASC],
                    'desc' => ['relation_types.name' => SORT_DESC],
                ],
                'related' => [
                    'asc' => ['tasks.rel_name' => SORT_ASC],
                    'desc' => ['tasks.rel_name' => SORT_DESC],
                ],
                'responsible' => [
                    'asc' => ['rProfile.name' => SORT_ASC],
                    'desc' => ['rProfile.name' => SORT_DESC],
                ],
                'creator' => [
                    'asc' => ['cProfile.name' => SORT_ASC],
                    'desc' => ['cProfile.name' => SORT_DESC],
                ],
                'closed' => [
                    'asc' => ['clProfile.name' => SORT_ASC],
                    'desc' => ['clProfile.name' => SORT_DESC],
                ],
                'branch' => [
                    'asc' => ['rBranch.code' => SORT_ASC],
                    'desc' => ['rBranch.code' => SORT_DESC],
                ],
                'created_at',
            ],
            'defaultOrder' => ['dateTime' => SORT_ASC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'and',
            ['=', 'tasks.status', $this->active ?? $this->getClosedValueByStatus($this->status)],
            ['=', 'tasks.c_user_id', $this->cid ?? $this->getCidValueByOwn($this->own)],
            ['=', 'tasks.user_id', $this->rid ?? $this->getRidValueByOwn($this->own)],
            ['=', 'tasks.close_user_id', $this->clid],
            ['=', 'tasks_types.id', $this->tpid],
            ['=', 'rBranch.code', $this->branch],
            ['=', 'tasks.relation_id', $this->relid],
            ['=', 'tasks.relation_type', $this->reltid],
            ['>=', 'tasks.created_at', $this->from],
            ['<', 'tasks.created_at', $this->to],
            ['>=', 'tasks.day', $this->fday],
            ['<', 'tasks.day', $this->tday],
        ]);

        $query->andFilterWhere([
            (isset($this->q) && $this->q !== '') ? 'or' : 'and',
            ['like', 'tasks.rel_name', $this->related ?? $this->q],
            ['like', 'tasks_types.name', $this->type ?? $this->q],
            ['like', 'cProfile.name', $this->creator ?? $this->q],
            ['like', 'rProfile.name', $this->responsible ?? $this->q],
            ['like', 'clProfile.name', $this->closed ?? $this->q],
        ]);

        $this->addDayCondition($query);

        $this->addRelatedTypeCondition($query);

        return $dataProvider;
    }

    /**
     * @param null $status
     * @return int|null
     */
    private function getClosedValueByStatus($status = null)
    {
        $status = $status ?? $this->status;

        switch ($status) {
            case self::STATUS_OPENED:
                $status = Tasks::STATUS_OPENED;
                break;
            case self::STATUS_CLOSED:
                $status = Tasks::STATUS_CLOSED;
                break;
        }

        return $status;
    }

    /**
     * @param null $own
     * @return int|null|string
     */
    private function getCidValueByOwn($own = null)
    {
        $own = $own ?? $this->own;

        return $own === self::OWN_CREATOR ? Yii::$app->user->id : null;
    }

    /**
     * @param null $own
     * @return int|null|string
     */
    private function getRidValueByOwn($own = null)
    {
        $own = $own ?? $this->own;

        return $own === self::OWN_RESPONSIBLE ? Yii::$app->user->id : null;
    }

    /**
     * @return array
     */
    public function ownLabels()
    {
        return [
            self::OWN_CREATOR => 'Я создатель',
            self::OWN_RESPONSIBLE => 'Я ответственный',
        ];
    }

    /**
     * @return array
     */
    public function statusLabels()
    {
        return [
            self::STATUS_OPENED => 'Открытые',
            self::STATUS_CLOSED => 'Закрытые',
        ];
    }

    /**
     * @return array
     */
    public function displayTypes()
    {
        return [
            self::TODAY => 'Сегодня',
            self::TOMORROW => 'Завтра',
            self::NEXT7 => 'Следующие 7 дней',
            self::ALL => 'Все',
        ];
    }

    /**
     * @return array
     */
    public function relatedTypes()
    {
        return [
            self::RELATED_NATURALS => 'Физ.лицо',
            self::RELATED_NATURAL_LEADS => 'Сделка физ.лица',
            self::RELATED_LEGALS => 'Юр.лицо',
            self::RELATED_LEGAL_LEADS => 'Сделка юр.лица',
        ];
    }

    /**
     * @param $query ActiveQuery
     * @param null $day
     */
    private function addDayCondition($query, $day = null)
    {
        $day = $day ?? $this->day;

        switch ($day) {
            case self::TODAY;
                $query->andWhere(['=', 'tasks.day', (new \DateTime())->format('Y-m-d')]);
                break;
            case self::TOMORROW:
                $query->andWhere(['=', 'tasks.day', (new \DateTime())->modify('+1 day')->format('Y-m-d')]);
                break;
            case self::NEXT7:
                $query->andWhere(['between', 'tasks.day', (new \DateTime())->format('Y-m-d'),
                    (new \DateTime())->modify('+7 day')->format('Y-m-d')]);
                break;
            case self::ALL:
                break;
        }
    }

    /**
     * @param $query ActiveQuery
     * @param null $type
     */
    private function addRelatedTypeCondition($query, $type = null)
    {
        $type = $type ?? $this->reltype;

        switch ($type) {
            case self::RELATED_LEGALS:
                $query->andWhere(['=', 'lt', Contacts::TYPE_LEGAL]);
                break;
            case self::RELATED_NATURALS:
                $query->andWhere(['=', 'nt', Contacts::TYPE_NATURAL]);
                break;
            case self::RELATED_LEGAL_LEADS:
                $query->andWhere(['=', 'lct', Contacts::TYPE_LEGAL]);
                break;
            case self::RELATED_NATURAL_LEADS:
                $query->andWhere(['=', 'lct', Contacts::TYPE_NATURAL]);
                break;
        }
    }


    /**
     * @param ActiveRecord $model
     * @return $this
     */
    public function relatedWith(ActiveRecord $model)
    {
        $className = (new \ReflectionClass($model))
            ->getName();

        if (!in_array($className, Tasks::morphMap())) {
            throw new InvalidParamException('Tasks can\'t be related with ' . $className);
        }

        $relationType = RelationTypes::findOne(['class_name' => $className]);

        if ($relationType == null) {
            throw new InvalidParamException($className . ' not found in ' . RelationTypes::tableName() . ' table');
        }

        $this->reltid = $relationType->id;
        $this->relid = $model->id;

        return $this;
    }
}
