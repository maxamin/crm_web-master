<?php

namespace app\models;

use dektrium\user\models\UserSearch as BaseUserSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UsersSearch extends BaseUserSearch
{
    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $branch;

    /** @var string */
    public $role;

    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            'searchSafe' => [['name', 'branch', 'role'], 'safe'],
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        $attributeLabels = [
            'id' => '#',
            'name' => 'ФИО',
            'branch' => 'Отделение',
            'role' => 'Роль',
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $attributeLabels);
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->finder->getUserQuery();

        $query->joinWith(['profile' => function ($q) {
            $q->joinWith(['branch']);
        }]);

        $query->joinWith(['authAssignments' => function ($q) {
            $q->joinWith(['authItem' => function ($q) {
                $q->onlyRoles();
            }]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'username',
                    'email',
                    'name' => [
                        'asc' => ['profile.name' => SORT_ASC],
                        'desc' => ['profile.name' => SORT_DESC],
                    ],
                    'created_at',
                    'last_login_at',
                    'branch' => [
                        'asc' => ['branch.code' => SORT_ASC],
                        'desc' => ['branch.code' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        if ($this->last_login_at !== null) {
            $date = strtotime($this->last_login_at);
            $query->andFilterWhere(['between', 'last_login_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'profile.name', $this->name])
            ->andFilterWhere(['like', 'branch.code', $this->branch])
            ->andFilterWhere(['like', 'auth_item.name', $this->role])
            ->andFilterWhere(['user.registration_ip' => $this->registration_ip])
            ->andFilterWhere(['user.id' => $this->id]);

        return $dataProvider;
    }
}