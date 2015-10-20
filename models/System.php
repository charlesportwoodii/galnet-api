<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "systems".
 *
 * @property integer $id
 * @property string $name
 * @property double $x
 * @property double $y
 * @property double $z
 * @property string $faction
 * @property integer $population
 * @property string $government
 * @property string $allegiance
 * @property string $state
 * @property string $security
 * @property string $primary_economy
 * @property boolean $needs_permit
 * @property integer $created_at
 * @property integer $updated_at
 */
class System extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'systems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'z'], 'number'],
            [['population', 'created_at', 'updated_at', 'id'], 'integer'],
            [['needs_permit'], 'boolean'],
            [['name', 'faction', 'government', 'allegiance', 'state'], 'string', 'max' => 255],
            [['security'], 'string', 'max' => 50],
            [['primary_economy'], 'string', 'max' => 75]
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
            'x' => 'X',
            'y' => 'Y',
            'z' => 'Z',
            'faction' => 'Faction',
            'population' => 'Population',
            'government' => 'Government',
            'allegiance' => 'Allegiance',
            'state' => 'State',
            'security' => 'Security',
            'primary_economy' => 'Primary Economy',
            'needs_permit' => 'Needs Permit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStations()
    {
        return $this->hasMany(Station::className(), ['system_id' => 'id']);
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
            'id'                => $this->id,
            'state'             => $this->state,
            'security'          => $this->security,
            'needs_permit'      => $this->needs_permit,
            'government'        => $this->government,
            'allegiance'        => $this->allegiance,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'faction', $this->faction]);
        $query->andFilterWhere(['like', 'primary_economy', $this->primary_economy]);

        // Add in soem additional filtering
        if (!isset($params['System']['populationMap']))
            $params['System']['populationMap'] = '=';

        if (isset($params['System']['population']) && in_array($params['System']['populationMap'], ['>', '>=', '=', '<', '<=']))
            $query->andFilterWhere([$params['System']['populationMap'], 'population', $this->population]);

        return $query;
    }
}
