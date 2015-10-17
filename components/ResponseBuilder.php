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
	public static function build(yii\db\ActiveQuery $query, $response=[])
	{
		$countQuery = clone $query;
		$count = $countQuery->count();
		$pages = new Pagination(['totalCount' => $count]);
		$models = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();
		
		$page = $pages->page+1;
		if ($page < Yii::$app->request->get('page'))
			throw new HttpException(404, 'There are no builds for page: ' . Yii::$app->request->get('page'));

		// Set some useful pagination headers
		$headers = Yii::$app->response->headers;

		$headers->set('X-Pagination-Per-Page', 20);
		$headers->set('X-Pagination-Current-Page', $page);
		$headers->set('X-Pagination-Total-Pages', $pages->pageCount);
		$headers->set('X-Pagination-Total-Entries', $count);

		foreach ($models as $model)
			$response[] = self::getBuildAttributes($model);
			
		return $response;
	}
	
	/**
	 * Returns  model safe attributes
	 * @param app\model\Build $model
	 * @return array
	 */
	public static function getBuildAttributes($model=NULL)
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
