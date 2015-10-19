<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "station_commodities".
 *
 * @property integer $station_id
 * @property integer $commodity_id
 * @property integer $supply
 * @property integer $buy_price
 * @property integer $sell_price
 * @property integer $demand
 * @property integer $collected_At
 * @property integer $update_count
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Commodities $commodity
 * @property Stations $station
 */
class StationCommodity extends \yii\db\ActiveRecord
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
        return 'station_commodities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_id', 'commodity_id', 'supply', 'buy_price', 'sell_price', 'demand', 'collected_At', 'update_count', 'created_at', 'updated_at'], 'integer'],
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
            'supply' => 'Supply',
            'buy_price' => 'Buy Price',
            'sell_price' => 'Sell Price',
            'demand' => 'Demand',
            'collected_At' => 'Collected  At',
            'update_count' => 'Update Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommodity()
    {
        return $this->hasOne(Commodities::className(), ['id' => 'commodity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStation()
    {
        return $this->hasOne(Stations::className(), ['id' => 'station_id']);
    }
}
