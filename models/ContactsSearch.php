<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * ContactsSearch represents the model behind the search form about `app\models\Contacts`.
 */
class ContactsSearch extends Contacts
{
    const OWNED_TYPES = [
        '1' => 'Я ответственный',
        '2' => 'Я создал',
    ];

    const STATUS_TYPES = [
        '1' => 'Активные',
        '0' => 'Не активные',
    ];

    private $ownedType;
    private $searchQuery;
    private $usersFio;
    private $branch;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'cities_id', 'c_user_id', 'r_user_id', 'type'], 'integer'],
            [['created_at', 'updated_at', 'name', 'okpo', 'website', 'address', 'ownedType', 'type', 'searchQuery', 'usersFio', 'branch'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Override load
     *
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $formName = $formName ? $formName : $this->formName();

        if (isset($data['type'])) {
            $this->type = $data['type'];
        }

        if (!isset($this->ownedType)) {
            switch ($data[$formName]['ownedType']) {
                case 1:
                    $this->r_user_id = Yii::$app->user->id;
                    break;
                case 2:
                    $this->c_user_id = Yii::$app->user->id;
                    break;
            }
        }

        $searchQuery = $data[$formName]['searchQuery'];

        if (isset($searchQuery) && $searchQuery !== '') {
            $this->okpo = $searchQuery;
            $this->name = $searchQuery;
            $this->usersFio = $searchQuery;
        }

        return parent::load($data, $formName);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @param bool $provider
     * @return ActiveDataProvider | ActiveRecord[]
     */
    public function search($params, $provider = true)
    {
        $query = Contacts::find();

        // add conditions that should always apply here

        $query->joinWith(['cUser creator' => function($q) {
            $q->joinWith('profile cProfile');
        }]);
        $query->joinWith(['rUser responsible' => function($q) {
            $q->joinWith(['profile rProfile' => function ($q) {
                $q->joinWith(['branch rBranch']);
            }]);
        }]);

        $dataProvider = null;

        if ($provider === true) {

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $dataProvider->setSort([
                'attributes' => [
                    'name',
                    'active',
                    'rUser.profile.name' => [
                        'asc' => ['rProfile.name' => SORT_ASC],
                        'desc' => ['rProfile.name' => SORT_DESC],
                    ],
                    'cUser.profile.name' => [
                        'asc' => ['cProfile.name' => SORT_ASC],
                        'desc' => ['cProfile.name' => SORT_DESC],
                    ],
                    'rUser.profile.branch.code' => [
                        'asc' => ['rBranch.name' => SORT_ASC],
                        'desc' => ['rBranch.name' => SORT_DESC],
                    ],
                    'created_at',
                ],
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'contacts.r_user_id' => $this->r_user_id,
            'contacts.c_user_id' => $this->c_user_id,
            'contacts.type' => $this->type,
            'contacts.active' => $this->active,
            'rBranch.code' => $this->branch,
        ]);

        $query->andFilterWhere([
            'or',
            ['in', 'contacts.okpo', $this->okpo],
            ['like', 'contacts.name', $this->name],
            ['like', 'rProfile.name', $this->usersFio],
            ['like', 'cProfile.name', $this->usersFio],
        ]);

        return $provider === true ? $dataProvider : $query->all();
    }

    /**
     * @return mixed
     */
    public function getOnlyActive()
    {
        return $this->onlyActive;
    }

    /**
     * @param $onlyActive
     */
    public function setOnlyActive($onlyActive)
    {
        $this->onlyActive = $onlyActive;
    }

    /**
     * @return mixed
     */
    public function getOnlyOwn()
    {
        return $this->onlyOwn;
    }

    /**
     * @param $onlyOwn
     */
    public function setOnlyOwn($onlyOwn)
    {
        $this->onlyOwn = $onlyOwn;
    }

    /**
     * @return mixed
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * @param mixed $searchQuery
     */
    public function setSearchQuery($searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    /**
     * @return mixed
     */
    public function getUsersFio()
    {
        return $this->usersFio;
    }

    /**
     * @param mixed $usersFio
     */
    public function setUsersFio($usersFio)
    {
        $this->usersFio = $usersFio;
    }

    /**
     * @return mixed
     */
    public function getOwnedType()
    {
        return $this->ownedType;
    }

    /**
     * @param mixed $ownedType
     */
    public function setOwnedType($ownedType)
    {
        $this->ownedType = $ownedType;
    }

    /**
     * @return mixed
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param mixed $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }
}
