<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "affiliated".
 *
 * @property integer $id
 * @property string $company_id
 * @property string $company
 * @property string $tax_number
 * @property string $address
 * @property string $tel
 * @property string $create_date
 *
 * @property AffiliatedTruck[] $affiliatedTrucks
 */
class Affiliated extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliated';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company','tax_number','tel','address'], 'required'],
            [['id'], 'integer'],
            [['address'], 'string'],
            [['create_date'], 'safe'],
            [['company_id'], 'string', 'max' => 10],
            [['company'], 'string', 'max' => 255],
            [['tel'], 'string','max'=>9,'length'=>9],
            [['tax_number'], 'string','max'=>13,'length'=>13],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'รหัสบริษัท',
            'company' => 'ชื่อบริษัท',
            'tax_number' => 'เลขผู้เสียภาษี',
            'address' => 'ที่อยู่',
            'tel' => 'เบอร์โทรศัพท์',
            'create_date' => 'วันที่บันทึก',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliatedTrucks()
    {
        return $this->hasMany(AffiliatedTruck::className(), ['company_id' => 'company_id']);
    }
    
    public function Get_history($company_id = null){
        $sql = "SELECT COUNT(*) AS TOTAL
                    FROM orders_transport_affiliated o 
                    WHERE o.company_id = '$company_id' " ;
        $rs = Yii::$app->db->createCommand($sql)->queryOne();
        return $rs['TOTAL'];
    }
}
