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
    public function getCommodities()
    {
        return $this->hasMany(StationCommodity::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStationEconomies()
    {
        return $this->hasMany(StationEconomy::className(), ['station_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystem()
    {
        return $this->hasOne(System::className(), ['id' => 'system_id']);
    }

    /**
     * Search method for filtering by multiple attributes
     * @param array $params
     * @return yii\db\Query
     */
    public function search($params=[])
    {
        unset($params['sort']);
        $query = self::find();

        if (!($this->load($params) && $this->validate()))
            throw new \yii\web\HttpException(400, 'Invalid request parameters');

        $query->andFilterWhere([
            'id'                    => $this->id,
            'has_shipyard'          => $this->has_shipyard,
            'has_outfitting'        => $this->has_outfitting,
            'has_rearm'             => $this->has_rearm,
            'has_repair'            => $this->has_repair,
            'has_refuel'            => $this->has_refuel,
            'has_commodities'       => $this->has_commodities,
            'type'                  => $this->type,
            'state'                 => $this->state,
            'max_landing_pad_size'  => $this->max_landing_pad_size,
            'government'            => $this->government,
            'allegiance'            => $this->allegiance,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'faction', $this->faction]);

        // Add in soem additional filtering
        if (!isset($params['Station']['starDistanceMap']))
            $params['Station']['starDistanceMap'] = '=';

        if (isset($params['Station']['distance_to_star']) && in_array($params['Station']['starDistanceMap'], ['>', '>=', '=', '<', '<=']))
            $query->andFilterWhere([$params['Station']['starDistanceMap'], 'distance_to_star', $this->distance_to_star]);

        return $query;
    }
}
