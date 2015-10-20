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
	 * @param app\model\Commodities $model
	 * @return array
	 */
	public static function systems($model=NULL)
	{
		if ($model === NULL)
			throw new \yii\base\Exception('Missing model data');
		
		return \yii\helpers\ArrayHelper::merge($model->attributes, [
			'stations' => $model->stations
		]);
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
