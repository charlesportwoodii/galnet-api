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
     * Returns a standardized X,Y,Z point
     * @return Sandfox\KDTree\Point
     */
    public function getPoint()
    {
        $point = new \Sandfox\KDTree\Point;
        $point[0] = $this->x;
        $point[1] = $this->y;
        $point[2] = $this->z;

        return $point;
    }

    /**
     * Retrieves all systems in ED known space as a KD Tree
     * @return \KDTree\KDTree
     */
    public static function getAllPoints($resetCache=false)
    {
        ini_set('memory_limit', '512M');
        $points = Yii::$app->cache->get('System::KDPoints');
        if ($points === false || $resetCache === true)
        {
            $points = [];

            foreach (System::find()->all() as $model)
                $points[] = $model->getPoint();

            Yii::$app->cache->set('System::KDPoints', $points);
        }

        $tree = Yii::$app->cache->get('System::KDPTree');
        if ($tree === false || $resetCache === true)
        {
            $tree = \Sandfox\KDTree\KDTree::build($points);
            Yii::$app->cache->set('System::KDPTree', serialize($tree));
            return $tree;
        }

        return unserialize($tree);
    }

    /**
     * Finds nearby systems
     * @param integer $count
     * @return array
     */
    public function getNearbySystems($count=16)
    {
        $response = Yii::$app->cache->get('Systems::Nearby::' . $this->id);
        if ($response === false)
        {
            $tree = System::getAllPoints();
            $results = new \Sandfox\KDTree\SearchResults($count);
            \Sandfox\KDTree\KDTree::nearestNeighbour($tree, $this->getPoint(), $results);
            
            $nearestPoints = [];
            $nearestModels = [];

            while($results->valid())
                $nearestPoints[] = $results->extract();

            foreach ($nearestPoints as $point)
            {
                $point = $point['data']->getPoint();

                $nearestModels[] = self::find()->select('id')->where([
                    'x' => $point->getCoordinates()[0],
                    'y' => $point->getCoordinates()[1],
                    'z' => $point->getCoordinates()[2]
                ])->one();
            }

            foreach ($nearestModels as $m)
                $response[] = $m['id'];

            $response = array_reverse($response);

            unset($response[0]);
            reset($response);

            Yii::$app->cache->set('Systems::Nearby::' . $this->id, $response, 43200);
        }

        return $response;
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
