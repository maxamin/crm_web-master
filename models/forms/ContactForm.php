<?php

namespace app\models\forms;


use Yii;
use yii\base\Model;
use app\models\Contacts;
use app\models\ContactsInfos;
use app\models\AdditionFieldsValues;
use app\models\ContactsLink;

class ContactForm extends Model
{
    /**
     * @var $contacts Contacts
     * @var $contactsInfos array ContactsInfos
     */
    private $contacts;
    private $contactsInfos;
    private $additionFieldsValues;
    private $contactsLink;

    public function rules()
    {
        return [
            [['Contacts'], 'required'],
            [['ContactsInfos', 'AdditionFieldsValues', 'ContactsLink'], 'safe'],
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

        if (!$this->contacts->save()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->saveContactsInfos()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->saveAdditionFieldsValues()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->saveContactsLink()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return true;
    }

    public function saveContactsInfos()
    {
        $keep = [];

        foreach ($this->contactsInfos as $contactsInfo) {
            $contactsInfo->contact_id = $this->contacts->id;

            if (!empty($contactsInfo->contact_value)) { //save if contact_value not empty

                if (!$contactsInfo->save(false)) {
                    return false;
                }

                $keep[] = $contactsInfo->id;
            }
        }

        $query = ContactsInfos::find()->andWhere(['contact_id' => $this->contacts->id]);

        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }

        foreach ($query->all() as $contactsInfo) {
            $contactsInfo->delete(true);
        }

        return true;
    }

    public function saveAdditionFieldsValues()
    {
        $keep = [];

        foreach ($this->additionFieldsValues as $additionFieldsValue) {
            $additionFieldsValue->foregin_table_id = $this->contacts->id;

            if (!empty($additionFieldsValue->value)) { //save if value not empty

                if (!$additionFieldsValue->save(false)) {
                    return false;
                }

                $keep[] = $additionFieldsValue->id;
            }
        }

        $query = AdditionFieldsValues::find()->andWhere(['foregin_table_id' => $this->contacts->id]);

        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }

        foreach ($query->all() as $additionFieldsValue) {
            $additionFieldsValue->delete(true);
        }

        return true;
    }

    public function saveContactsLink()
    {
        $keep = [];

        foreach ($this->contactsLink as $contactsLink) {
            $contactsLink->contacts_id = $this->contacts->id;

            if (!$contactsLink->save(false)) {
                return false;
            }

            $keep[] = $contactsLink->id;
        }

        $query = ContactsLink::find()->andWhere(['contacts_id' => $this->contacts->id]);

        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }

        foreach ($query->all() as $contactsLink) {
            $contactsLink->delete(true);
        }

        return true;
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function setContacts($contacts)
    {
        if ($contacts instanceof Contacts) {
            $this->contacts = $contacts;
        } elseif (is_array($contacts)) {
            $this->contacts->setAttributes($contacts);
        }
    }

    public function getContactsInfo($key)
    {
        $contactsInfo = $key && strpos($key, 'new') === false ? ContactsInfos::findOne($key) : false;

        if (!$contactsInfo) {
            $contactsInfo = new ContactsInfos();
            $contactsInfo->loadDefaultValues();
        }

        return $contactsInfo;
    }

    public function getContactsInfos()
    {
        if ($this->contactsInfos === null) {
            $this->contactsInfos = $this->contacts->isNewRecord ? [] : $this->contacts->contactsInfos;
        }

        return $this->contactsInfos;
    }

    public function setContactsInfos($contactsInfos)
    {
        unset($contactsInfos['temp_key']); //remove template input
        $this->contactsInfos = [];

        foreach ($contactsInfos as $key => $info) {
            if (is_array($info)) {
                $this->contactsInfos[$key] = $this->getContactsInfo($key);
                $this->contactsInfos[$key]->setAttributes($info);
            } elseif ($info instanceof ContactsInfos) {
                $key = $info->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $info->id;
                $this->contactsInfos[$key] = $info;
            }
        }
    }

    public function getAdditionFieldsValue($key)
    {
        $additionFieldsValue = $key && strpos($key, 'new') === false ? AdditionFieldsValues::findOne($key) : false;

        if (!$additionFieldsValue) {
            $additionFieldsValue = new AdditionFieldsValues();
            $additionFieldsValue->loadDefaultValues();
        }

        return $additionFieldsValue;
    }

    public function getAdditionFieldsValues()
    {
        if ($this->additionFieldsValues === null) {
            $this->additionFieldsValues = $this->contacts->isNewRecord ? [] : $this->contacts->additionalValues;
        }

        return $this->additionFieldsValues;
    }

    public function setAdditionFieldsValues($additionFieldsValues)
    {
        $this->additionFieldsValues = [];

        foreach ($additionFieldsValues as $key => $additionFieldsValue) {
            if (is_array($additionFieldsValue)) {
                $this->additionFieldsValues[$key] = $this->getAdditionFieldsValue($key);
                $this->additionFieldsValues[$key]->setAttributes($additionFieldsValue);
            } elseif ($additionFieldsValue instanceof AdditionFieldsValues) {
                $key = $additionFieldsValue->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $additionFieldsValue->id;
                $this->additionFieldsValues[$key] = $additionFieldsValue;
            }
        }
    }

    public function getContactsLinkByKey($key)
    {
        $contactsLink = $key && strpos($key, 'new') === false ? ContactsLink::findOne($key) : false;

        if (!$contactsLink) {
            $contactsLink = new ContactsLink();
            $contactsLink->loadDefaultValues();
        }

        return $contactsLink;
    }

    /**
     * @return mixed
     */
    public function getContactsLink()
    {
        if ($this->contactsLink === null) {
            $this->contactsLink = $this->contacts->isNewRecord ? [] : $this->contacts->contactsLinks0;
        }

        return $this->contactsLink;
    }

    /**
     * @param mixed $contactsLink
     */
    public function setContactsLink($contactsLink)
    {
        unset($contactsLink['temp_key']);
        $this->contactsLink = [];

        foreach ($contactsLink as $key => $link) {
            if (is_array($link)) {
                $this->contactsLink[$key] = $this->getContactsLinkByKey($key);
                $this->contactsLink[$key]->setAttributes($link);
            } elseif ($link instanceof ContactsLink) {
                $key = $link->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $link->id;
                $this->contactsInfos[$key] = $link;
            }
        }
    }

    public function errorSummary($form)
    {
        $errorList = $form->errorSummary($this->getAllModels(), [
            'header' => '<p><i class="fa fa-fw fa-exclamation-triangle"></i>При попытке сохранения были обнаружены следующие ошибки:</p>',
        ]);
        $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error

        return $errorList;
    }

    private function getAllModels()
    {
        $models = ['Contacts' => $this->contacts];

        foreach ($this->contactsInfos as $id => $contactsInfo) {
            $models['ContactsInfos-'.$id] = $contactsInfo;
        }

        foreach ($this->additionFieldsValues as $id => $additionFieldsValue) {
            $models['AdditionFieldsValues-'.$id] = $additionFieldsValue;
        }

        foreach ($this->contactsLink as $id => $contactsLink) {
            $models['ContactsLink-'.$id] = $contactsLink;
        }

        return $models;
    }
}