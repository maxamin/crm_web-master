<?php

namespace app\models\forms;

use app\models\ContactsImport;
use app\models\Natural;
use PHPExcel_IOFactory;
use Yii;
use yii\base\Model;

use app\models\Users;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class NaturalImportForm extends Model
{
    /* ID типа контакта по ключу */
    const CONTACT_TYPE_ID = [
        'CELLULAR' => 1,
        'HOME' => 2,
        'WORK' => 3,
        'FAX' => 4,
        'OTHER' => 5,
        'EMAIL' => 6,
        'WORK_EMAIL' => 7,
        'SKYPE' => 8,
        'OTHER_CONTACT' => 9,
    ];

    const DEFAULT_CONTACT_TYPE = 'CELLULAR';
    const IMPORT_COLS = 13;
    const CONTACTS_INFO_DELIMITER = ';';
    const CONTACTS_INFO_KEY_DELIMITER = ':';

    /**
     * @var integer
     */
    public $r_user_id;
    /**
     * @var $file UploadedFile
     */
    public $file;

    /**
     * @var NaturalForm
     */
    private $naturalForm;

    /**
     * @var integer
     */
    private $errorRow;

    /**
     * @param string $key
     * @return integer|null
     */
    private static function getContactTypeIdByKey($key)
    {
        return self::CONTACT_TYPE_ID[mb_strtoupper($key)] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_user_id', 'file'], 'required'],
            [['r_user_id'], 'integer'],
            [['r_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(),
                'targetAttribute' => ['r_user_id' => 'id']],
            [['file'], 'file', 'extensions' => ['xls', 'xlsx'], 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->r_user_id = Yii::$app->user->id;
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_user_id' => 'Ответственный',
            'file' => 'Файл',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        $contactsImport = new ContactsImport();
        $contactsImport->file = $this->file;

        if (!($contactsImport->upload() && $contactsImport->save())) {
            $transaction->rollBack();
            $this->addErrors($contactsImport->getFirstErrors());
            return false;
        }

        try {
            $inputFileType = PHPExcel_IOFactory::identify($contactsImport->getFilePath());
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($contactsImport->getFilePath());
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('file', 'Не удалось считать «' . $contactsImport->getAttributeLabel('file') . '».');
            return false;
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // начало со второй строки (пропускаем заголовок)
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);

            if (count($rowData[0]) < self::IMPORT_COLS) {
                $transaction->rollBack();
                $this->errorRow = $row;
                $this->addError('file', 'Неверный формат импорта «' . $contactsImport->getAttributeLabel('file') . '».');
                return false;

            }

            $natural = [
                'name' => (string) $rowData[0][0],
                'okpo' => (string) $rowData[0][1],
                'website' => (string) $rowData[0][2],
                'address' => (string) $rowData[0][3],
                'active' => $rowData[0][4],
                'cities_id' => $rowData[0][5],
                'description' => (string) $rowData[0][6],
                'r_user_id' => $this->r_user_id,
                'import_id' => $contactsImport->id,
            ];

            $contactsInfosData = explode(self::CONTACTS_INFO_DELIMITER, trim($rowData[0][7], self::CONTACTS_INFO_DELIMITER));

            $contactsInfos = [];

            foreach ($contactsInfosData as $info) {
                $info = explode(self::CONTACTS_INFO_KEY_DELIMITER, $info);

                if (count($info) >= 2) {
                    $key = trim($info[0]);
                    unset($info[0]);
                    $value = trim(implode(self::CONTACTS_INFO_KEY_DELIMITER, $info));
                } else {
                    $key = self::DEFAULT_CONTACT_TYPE;
                    $value = trim($info[0]);
                }

                $contactsInfos[] = ['contact_type_id' => self::getContactTypeIdByKey($key), 'contact_value' => $value];
            }

            $additionFieldsValues = [];

            for ($col = 8; $col <= 13; $col++) {
                $additionFieldId = $col - 7; //от 1 до 6
                $additionFieldsValues[] = ['addition_field_id' => $additionFieldId, 'value' => (string) $rowData[0][$col]];
            }

            $data = [
                'Natural' => $natural,
                'AdditionFieldsValues' => $additionFieldsValues,
                'ContactsInfos' => $contactsInfos,
                'ContactsLink' => [],
            ];

            $this->naturalForm = new NaturalForm();
            $this->naturalForm->natural = new Natural(['scenario' => Natural::SCENARIO_IMPORT]);
            $this->naturalForm->natural->loadDefaultValues();

            $this->naturalForm->setAttributes($data);

            if (!$this->naturalForm->save()) {
                $transaction->rollBack();
                $this->errorRow = $row;
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    public function errorSummary($form)
    {
        $onRow = $this->errorRow === null ? '' : '<b> «строки #' . $this->errorRow . '»</b>';

        $errorList = $form->errorSummary($this->getAllModels(), [
            'header' => '<p><i class="fa fa-fw fa-exclamation-triangle"></i>При попытке сохранения' . mb_strtoupper($onRow) . ' были обнаружены следующие ошибки:</p>',
        ]);
        $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error

        return $errorList;
    }

    public function getAllModels()
    {
        $models = [self::formName() => $this];

        if ($this->naturalForm) {
            $models = ArrayHelper::merge($models, $this->naturalForm->getAllModels());
        }

        return $models;
    }
}