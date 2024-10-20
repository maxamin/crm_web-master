<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NaturalSearch represents the model behind the search form about `app\models\Natural`.
 */
class NaturalSearch extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const OWN_CREATOR = 'creator';
    const OWN_RESPONSIBLE = 'responsible';

    /** @var string */
    public $q;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var integer */
    public $okpo;

    /** @var string */
    public $creator;

    /** @var string */
    public $responsible;

    /** @var integer */
    public $active;

    /** @var string */
    public $status;

    /** @var string */
    public $branch;

    /** @var integer */
    public $rid;

    /** @var integer */
    public $cid;

    /** @var string */
    public $own;

    /** @var string */
    public $createdAt;

    /** @var string */
    public $from;

    /** @var string */
    public $to;


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
            [['q', 'name', 'creator', 'responsible', 'status', 'branch', 'own', 'createdAt', 'from', 'to', 'description'], 'string'],
            [['okpo', 'cid', 'rid'], 'integer'],
            [['active'], 'boolean'],
            [['from', 'to'], 'filter', 'filter' => function ($value) {

                return !empty($value) ? Yii::$app->formatter->asDate($value, 'php:Y-m-d') : null;
            }],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Natural::find();

        $query->joinWith(['cUser creator' => function($q) {
            $q->joinWith('profile cProfile');
        }]);

        $query->joinWith(['rUser responsible' => function($q) {
            $q->joinWith(['profile rProfile' => function ($q) {
                $q->joinWith(['branch rBranch']);
            }]);
        }]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'name',
                'description',
                'status' => [
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                ],
                'responsible' => [
                    'asc' => ['rProfile.name' => SORT_ASC],
                    'desc' => ['rProfile.name' => SORT_DESC],
                ],
                'creator' => [
                    'asc' => ['cProfile.name' => SORT_ASC],
                    'desc' => ['cProfile.name' => SORT_DESC],
                ],
                'branch' => [
                    'asc' => ['rBranch.code' => SORT_ASC],
                    'desc' => ['rBranch.code' => SORT_DESC],
                ],
                'created_at',
            ],
            'defaultOrder' => ['created_at' => SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'and',
            ['=', 'natural.active', $this->active ?? $this->getActiveValueByStatus($this->status)],
            ['=', 'natural.c_user_id', $this->cid ?? $this->getCidValueByOwn($this->own)],
            ['=', 'natural.r_user_id', $this->rid ?? $this->getRidValueByOwn($this->own)],
            ['=', 'rBranch.code', $this->branch],
            ['>=', 'natural.created_at', $this->from],
            ['<', 'natural.created_at', $this->to],
        ]);

        $query->andFilterWhere([
            (isset($this->q) && $this->q !== '') ? 'or' : 'and',
            ['like', 'natural.name', $this->name ?? $this->q],
            ['like', 'natural.description', $this->description ?? $this->q],
            ['=', 'natural.okpo', $this->okpo ?? $this->q],
            ['like', 'cProfile.name', $this->creator ?? $this->q],
            ['like', 'rProfile.name', $this->responsible ?? $this->q],
        ]);

        return $dataProvider;
    }


    /**
     * @param null $status
     * @return int|null
     */
    private function getActiveValueByStatus($status = null)
    {
        $status = $status ?? $this->status;
        $active = null;

        switch ($status) {
            case self::STATUS_ACTIVE:
                $active = Natural::STATUS_ACTIVE;
                break;
            case self::STATUS_INACTIVE:
                $active = Natural::STATUS_INACTIVE;
                break;
        }

        return $active;
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
            self::STATUS_ACTIVE => 'Активные',
            self::STATUS_INACTIVE => 'Не активные',
        ];
    }
}
