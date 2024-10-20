<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * LeadsSearch represents the model behind the search form about `app\models\Leads`.
 */
class LeadsSearch extends Model
{
    const OWN_CREATOR = 'creator';
    const OWN_RESPONSIBLE = 'responsible';

    /** @var string */
    public $q;

    /** @var string */
    public $product;

    /** @var string */
    public $comment;

    /** @var integer */
    public $sum;

    /** @var string */
    public $creator;

    /** @var string */
    public $responsible;

    /** @var string */
    public $state;

    /** @var integer */
    public $stid;

    /** @var string */
    public $contact;

    /** @var integer */
    public $conid;

    /** @var integer */
    public $contype;

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
            [['q', 'product', 'creator', 'responsible', 'branch', 'own', 'createdAt', 'from', 'to',
                'state', 'contact', 'comment'], 'string'],
            [['cid', 'rid', 'conid', 'stid', 'sum', 'contype'], 'integer'],
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
     * @return ActiveDataProvider|ActiveRecord[]
     */
    public function search($params)
    {
        $query = Leads::find();

        $query->joinWith(['leadsProducts' => function ($q) {
            $q->joinWith(['product']);
        }]);

        $query->select([
            new Expression('CONVERT(GROUP_CONCAT(DISTINCT `products`.`name` ORDER BY `products`.`name` SEPARATOR ", " ) USING "utf8") AS `products`'),
            'leads.*'
        ]);

        $query->groupBy(['leads.id']);

        $query = Leads::find()->from(['leads' => $query]);

        if (!Yii::$app->user->can('manageAllLeads')) {
            $query->onlyOwnBranch();
        }

        $query->joinWith(['cUser creator' => function ($q) {
            $q->joinWith('profile cProfile');
        }]);
        $query->joinWith(['rUser responsible' => function ($q) {
            $q->joinWith(['profile rProfile' => function ($q) {
                $q->joinWith(['branch rBranch']);
            }]);
        }]);

        $query->joinWith('contacts');
        $query->joinWith('leadStatus');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'sum',
                'comment',
                'product' => [
                    'asc' => ['leads.products' => SORT_ASC],
                    'desc' => ['leads.products' => SORT_DESC],
                ],
                'contype' => [
                    'asc' => ['contacts.type' => SORT_ASC],
                    'desc' => ['contacts.type' => SORT_DESC],
                ],
                'contact' => [
                    'asc' => ['contacts.name' => SORT_ASC],
                    'desc' => ['contacts.name' => SORT_DESC],
                ],
                'state' => [
                    'asc' => ['lead_statuses.name' => SORT_ASC],
                    'desc' => ['lead_statuses.name' => SORT_DESC],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'and',
            ['=', 'leads.c_user_id', $this->cid ?? $this->getCidValueByOwn($this->own)],
            ['=', 'leads.r_user_id', $this->rid ?? $this->getRidValueByOwn($this->own)],
            ['=', 'leads.contacts_id', $this->conid],
            ['=', 'leads.sum', $this->sum],
            ['=', 'contacts.type', $this->contype],
            ['=', 'lead_statuses.id', $this->stid],
            ['=', 'rBranch.code', $this->branch],
            ['>=', 'leads.created_at', $this->from],
            ['<', 'leads.created_at', $this->to],
        ]);

        $query->andFilterWhere([
            (isset($this->q) && $this->q !== '') ? 'or' : 'and',
            ['like', 'leads.products', $this->product ?? $this->q],
            ['like', 'leads.comment', $this->comment ?? $this->q],
            ['like', 'cProfile.name', $this->creator ?? $this->q],
            ['like', 'rProfile.name', $this->responsible ?? $this->q],
            ['like', 'lead_statuses.name', $this->state ?? $this->q],
            ['like', 'contacts.name', $this->contact ?? $this->q],
        ]);

        return $dataProvider;
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
}
