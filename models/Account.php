<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property integer $id
 * @property string $account_number
 * @property string $account_name
 * @property integer $saving_type
 * @property integer $bank_name
 * @property integer $status
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_number','account_number','account_name','saving_type'], 'required'],
            [['saving_type', 'bank_name', 'status'], 'integer'],
            [['account_number'], 'string', 'max' => 10],
            [['account_name'], 'string', 'max' => 100],
            [['account_number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ลำดับ',
            'account_number' => 'เลขที่บัญชี',
            'account_name' => 'ชื่อเจ้าของบัญชี',
            'saving_type' => 'ประเภทบัญชี',
            'bank_name' => 'ธนาคาร',
            'status' => 'สถานะของบัญชี',
        ];
    }
    function get_status($status) {
        if ($status=1){
            $statusview="Active";
        }  else {
            $statusview="Inactive";
        } 
        return $statusview;
    }
}