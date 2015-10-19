<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "stations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $system_id
 * @property string $max_landing_pad_size
 * @property integer $distance_to_star
 * @property string $faction
 * @property string $government
 * @property string $allegiance
 * @property string $state
 * @property string $type
 * @property boolean $has_blackmarket
 * @property boolean $has_commodities
 * @property boolean $has_refuel
 * @property boolean $has_repair
 * @property boolean $has_rearm
 * @property boolean $has_outfitting
 * @property boolean $has_shipyard
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property StationCommodities[] $stationCommodities
 * @property StationEconomies[] $stationEconomies
 * @property StationExportCommodities[] $stationExportCommodities
 * @property StationImportCommodities[] $stationImportCommodities
 * @property StationProhibitedCommodities[] $stationProhibitedCommodities
 * @property Systems $system
 */
class Station extends \yii\db\ActiveRecord
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
        return 'stations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_id', 'distance_to_star', 'created_at', 'updated_at'], 'integer'],
            [['has_blackmarket', 'has_commodities', 'has_refuel', 'has_repair', 'has_rearm', 'has_outfitting', 'has_shipyard'], 'boolean'],
            [['name', 'max_landing_pad_size', 'faction', 'government', 'allegiance', 'state', 'type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'system_id' => 'System ID',
            'max_landing_pad_size' => 'Max Landing Pad Size',
            'distance_to_star' => 'Distance To Star',
            'faction' => 'Faction',
            'government' => 'Government',
            'allegiance' => 'Allegiance',
            'state' => 'State',
            'type' => 'Type',
            'has_blackmarket' => 'Has Blackmarket',
            'has_commodities' => 'Has Commodities',
            'has_refuel' => 'Has Refuel',
            'has_repair' => 'Has Repair',
            'has_rearm' => 'Has Rearm',
            'has_outfitting' => 'Has Outfitting',
            'has_shipyard' => 'Has Shipyard',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStationCommodities()
    {
        return $this->hasMany(StationCommodities::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStationEconomies()
    {
        return $this->hasMany(StationEconomies::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStationExportCommodities()
    {
        return $this->hasMany(StationExportCommodities::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStationImportCommodities()
    {
        return $this->hasMany(StationImportCommodities::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStationProhibitedCommodities()
    {
        return $this->hasMany(StationProhibitedCommodities::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystem()
    {
        return $this->hasOne(Systems::className(), ['id' => 'system_id']);
    }
}
