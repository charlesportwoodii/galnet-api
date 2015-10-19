<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "station_export_commodities".
 *
 * @property integer $station_id
 * @property integer $commodity_id
 * @property integer $created_at
 *
 * @property Stations $station
 */
class StationExportCommodity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'station_export_commodities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_id', 'commodity_id', 'created_at'], 'integer'],
            [['station_id', 'commodity_id'], 'unique', 'targetAttribute' => ['station_id', 'commodity_id'], 'message' => 'The combination of Station ID and Commodity ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'station_id' => 'Station ID',
            'commodity_id' => 'Commodity ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStation()
    {
        return $this->hasOne(Stations::className(), ['id' => 'station_id']);
    }
}
