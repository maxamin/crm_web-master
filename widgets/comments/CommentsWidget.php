<?php

namespace app\widgets\comments;

use app\models\RelationTypes;
use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;

use app\models\Comments;
use yii\helpers\ArrayHelper;

class CommentsWidget extends Widget
{

    const SHOW_ALL = 1;

    /* @var $model ActiveRecord */
    public $model;
    public $limit = 3;

    private $showMore = true;
    private $comments;
    private $count;

    public function init()
    {
        parent::init();
    }

    /**
     * Lists all contacts Leads models.
     * @return mixed
     */
    public function run()
    {
        $commentForm = $this->createComment();
        $this->findComments();

        return $this->render('comments', [
            'commentForm' => $commentForm,
            'comments' => $this->comments,
            'count' => $this->count,
            'showMore' => $this->showMore,
        ]);
    }

    /**
     * @return Comments
     */
    protected function createComment()
    {
        $model = $this->initModelForm();

        if ($post = Yii::$app->request->post()) {

            $model = $this->initModelForm();

            $model->load($post);

            if ($model->save()) {

                $model = $this->initModelForm();
            }
        }

        return $model;
    }

    /**
     * @return Comments
     */
    private function initModelForm()
    {
        $model = new Comments();
        $model->loadDefaultValues();
        $model->relation_id = $this->model->id;
        $model->relation_type = RelationTypes::findOne(['class_name' => (new \ReflectionClass($this->model))->getName()])->id;

        return $model;
    }

    protected function findComments()
    {
        $params = Yii::$app->request->queryParams;

        if (isset($params['show']) && $params['show'] == self::SHOW_ALL) {
            $this->limit = null;
        }

        $query = Comments::find()->whereRelatedWith($this->model);

        $this->count = $query->count();

        $this->comments = $query->last($this->limit)->all();
        ArrayHelper::multisort($this->comments, ['created_at'], [SORT_ASC]);

        if ($this->count == count($this->comments)) {
            $this->showMore = false;
        }

        if ($this->count <= $this->limit) {
            $this->showMore = null;
        }
    }
}