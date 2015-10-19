<?php

namespace app\controllers;

use app\components\ResponseBuilder;
use app\models\Commodity;
use app\models\CommodityCategory;

use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\Inflector;

use Yii;

class EddbController extends \yii\web\Controller
{
	/**
	 * @internal
	 * Only allows POST requests to the hook endpoints
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'commodities'  	=> ['get'],
					'systems'		=> ['get']
				],
			],
			'corsFilter' => [
				'class' => Cors::className(),
				'cors' => [
			   		'Origin' => ['*'],
			   		'Access-Control-Request-Method' => ['GET', 'HEAD']
				]
			],
		];
	}

	/**
	 * Paginated endpoint for display all commodities from Eddb
	 * @return array
	 */
	public function actionCommodities()
	{
		$query = Commodity::find()
			->orderBy('id asc');

		if (Yii::$app->request->get('category', false))
		{
			// Find the requested category first
			$category = CommodityCategory::find()->where([
				'name' => Inflector::humanize(Yii::$app->request->get('category', NULL))
			])->one();

			if ($category === NULL)
				throw new HttpException(404, 'Could not find commodities for the requested category');

			// Append it to the query
			$query->andWhere(['category_id' => $category->id]);
		}

		// Also provide filtering by name
		if (Yii::$app->request->get('name', false))
			$query->andWhere(['name' => Inflector::humanize(Yii::$app->request->get('name', 'nothing'))]);

		return ResponseBuilder::build($query, 'commodities');
	}

	public function actionSystems()
	{
		$query = System::find()
			->orderBy('id asc');

		return ResponseBuilder::build($query, 'systems');
	}
}