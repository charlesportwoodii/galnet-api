<?php

namespace app\components;

use app\models\News;

use yii\data\Pagination;
use yii\web\HttpException;
use Yii;

class ResponseBuilder
{
	/**
	 * Builds a response object with pagination support
	 * @param yii\db\ActiveQuery $query
	 * @param array $response
	 * @return array
	 */
	public static function build(yii\db\ActiveQuery $query, $name, $order, $response=[])
	{
		$countQuery = clone $query;
		$count = $countQuery->count();
		$pages = new Pagination(['totalCount' => $count]);

		// Re-Add the order by so it doesn't affect the COUNT query
		$order = explode(' ', $order);
		if (!isset($order[1]))
			$order[1] = 'asc';

		if (!in_array($order[0], array_keys(Yii::$app->db->getTableSchema($name)->columns)))
			throw new HttpException(400, 'Invalid sort paramter');

		if (!in_array($order[1], ['asc', 'desc']))
			$order[1] = 'asc';

		$query->orderBy($order[0] . ' ' . $order[1]);

		$models = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();
		
		$page = $pages->page+1;
		if ($page < Yii::$app->request->get('page'))
			throw new HttpException(404);

		if (!method_exists(get_class(), $name))
			throw new HttpException(400, 'Invalid request');

		// Set some useful pagination headers
		$headers = Yii::$app->response->headers;

		$headers->set('X-Pagination-Per-Page', 20);
		$headers->set('X-Pagination-Current-Page', $page);
		$headers->set('X-Pagination-Total-Pages', $pages->pageCount);
		$headers->set('X-Pagination-Total-Entries', $count);

		foreach ($models as $model)
			$response[] = self::$name($model);
			
		return $response;
	}
	
	/**
	 * Returns model safe attributes
	 * @param app\model\Commodities $model
	 * @return array
	 */
	public static function commodities($model=NULL)
	{
		if ($model === NULL)
			throw new \yii\base\Exception('Missing model data');
		
		return [
			'id'			=> $model->id,
			'name'			=> $model->name,
			'average_price' => $model->average_price,
			'category' 		=> [
				'id'   => $model->category->id,
				'name' => $model->category->name
			]
		];
	}

	/**
	 * Returns model safe attributes
	 * @param app\model\System $model
	 * @return array
	 */
	public static function systems($model=NULL)
	{
		if ($model === NULL)
			throw new \yii\base\Exception('Missing model data');
		
		$stations = $model->stations;
		foreach ($stations as $k=>$v)
			unset($stations[$k]['system_id']);

		return \yii\helpers\ArrayHelper::merge($model->attributes, [
			'stations' => $stations
		]);
	}

	/**
	 * Returns model safe attributes
	 * @param app\model\Station $model
	 * @return array
	 */
	public static function stations($model=NULL)
	{
		if ($model === NULL)
			throw new \yii\base\Exception('Missing model data');
		
		$attributes = $model->attributes;
		unset($attributes['system_id']);

		$modelClass = get_class($model);
		$neighbors = $modelClass::find()
			->select(['id', 'name', 'distance_to_star'])
			->where(['system_id' => $model->system_id])
			->andWhere('id !=' . $model->id)
			->all();


		foreach ($model->stationEconomies as $m)
			$economies[] = $m->name;

		$commodities = $model->commodities;
		return \yii\helpers\ArrayHelper::merge($attributes, [
			'system' 		=> $model->system,
			'economies' 	=> $economies,
			'commodities' 	=> [
				'listings' 		=> self::getCommoditiesClean($commodities, 'listings'),
				'imports' 		=> self::getCommoditiesClean($commodities, 'import_commodities'),
				'exports' 		=> self::getCommoditiesClean($commodities, 'export_commodities'),
				'prohibitied' 	=> self::getCommoditiesClean($commodities, 'prohibited_commodities')
			],
			'neighbors' => $neighbors
		]);
	}

	/**
	 * Retrieves a clean listing of commodities
	 * @param app\model\StationCommodity
	 * @param array $response
	 * @return array
	 */
	public static function getCommoditiesClean($model, $type, $response=[])
	{
		foreach ($model as $m)
		{
			if ($m->type == $type)
			{
				$data = [
					'commodity_id' => $m->commodity_id,
					'name'			=> $m->commodity->name,
				];

				if ($type == 'listings')
				{
					$data['supply'] = $m->supply;
					$data['demand'] = $m->demand;
					$data['buy_price'] = $m->buy_price;
					$data['sell_price'] = $m->sell_price;
				}
				$response[] = $data;
			}
		}

		return $response;
	}

	/**
	 * Returns  model safe attributes
	 * @param app\model\News $model
	 * @return array
	 */
	public static function news($model=NULL)
	{
		if ($model === NULL)
			throw new \yii\base\Exception('Missing model data');
		
		return [
			'uid' 						=> $model->uid,
			'tite' 						=> $model->title,
			'content' 					=> $model->content,
			'published' 				=> $model->published_at,
			'galnet_publication_time' 	=> $model->published_at_native,
			'url' 						=> Yii::$app->params['galnet']['url'] . 'galnet/uid/' . $model->uid
		];
	}
}
