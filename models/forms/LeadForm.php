<?php

namespace app\models\forms;


use Yii;
use yii\base\Model;

use app\models\Leads;
use app\models\LeadsProducts;

class LeadForm extends Model
{
    /**
     * @var $leads Leads
     * @var $leadsProducts array LeadsProducts
     */
    private $leads;
    private $leadsProducts;
    public $message;

    public function rules()
    {
        return [
            [['Leads'], 'required'],
            [['message'], 'checkProductsCount', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['LeadsProducts'], 'safe'],
        ];
    }

    public function afterValidate()
    {
        if (!Model::validateMultiple($this->getAllModels())) {
            $this->addError(null);
        }

        parent::afterValidate();
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        if (!$this->leads->save()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->saveLeadsProducts()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return true;
    }

    public function saveLeadsProducts()
    {
        $keep = [];

        foreach ($this->leadsProducts as $leadProduct) {
            $leadProduct->leads_id = $this->leads->id;

            if (!$leadProduct->save(false)) {
                return false;
            }

            $keep[] = $leadProduct->id;
        }

        $query = LeadsProducts::find()->andWhere(['leads_id' => $this->leads->id]);

        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }

        foreach ($query->all() as $leadProduct) {
            $leadProduct->delete(true);
        }

        return true;
    }

    public function getLeads()
    {
        return $this->leads;
    }

    public function setLeads($leads)
    {
        if ($leads instanceof Leads) {
            $this->leads = $leads;
        } elseif (is_array($leads)) {
            $this->leads->setAttributes($leads);
        }
    }

    public function getLeadsProduct($key)
    {
        $leadProduct = $key && is_int($key) === false ? LeadsProducts::findOne($key) : false;

        if (!$leadProduct) {
            $leadProduct = new LeadsProducts();
            $leadProduct->loadDefaultValues();
        }

        return $leadProduct;
    }

    public function getLeadsProducts()
    {
        if ($this->leadsProducts === null) {
            $this->leadsProducts = $this->leads->isNewRecord ? [] : $this->leads->leadsProducts;
        }

        return $this->leadsProducts;
    }

    public function setLeadsProducts($leadsProducts)
    {
        $this->leadsProducts = [];

        foreach ($leadsProducts as $key => $leadProduct) {
            if (is_array($leadProduct)) {
                $this->leadsProducts[$key] = $this->getLeadsProduct($key);
                $this->leadsProducts[$key]->setAttributes($leadProduct);
            } elseif ($leadProduct instanceof LeadsProducts) {
                $key = $leadProduct->isNewRecord ? Yii::$app->security->generateRandomString() : $leadProduct->id;
                $this->leadsProducts[$key] = $leadProduct;
            }
        }
    }

    public function errorSummary($form)
    {
        $models = $this->getAllModels();
        $models['this'] = $this;

        $errorList = $form->errorSummary($models, [
            'header' => '<p><i class="fa fa-fw fa-exclamation-triangle"></i>При попытке сохранения были обнаружены следующие ошибки:</p>',
        ]);

        return $errorList;
    }

    public function getAllModels()
    {
        $models = [
            'Leads' => $this->leads,
        ];

        if (isset($this->leadsProducts) && is_array($this->leadsProducts)) {
            foreach ($this->leadsProducts as $id => $leadProduct) {
                $models['LeadsProducts-' . $id] = $leadProduct;
            }
        }

        return $models;
    }


    /**
     * Override
     *
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        $this->leadsProducts = [];

        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    public function checkProductsCount($attribute)
    {
        if (count($this->leadsProducts) <= 0) {
            $this->addError($attribute, 'Необходимо заполнить «Продукты».');
        }
    }
}