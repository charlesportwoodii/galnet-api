<?php

namespace app\controllers;

use app\components\ResponseBuilder;
use app\models\System;

use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\Inflector;

use Yii;

class SystemsController extends \yii\rest\Controller
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
					'index'  	=> ['get'],
					'view'  	=> ['get'],
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
	public function actionIndex()
	{
		$allowedSearchFilters = [
			'name' 				=> NULL,
			'faction' 			=> NULL,
			'government' 		=> NULL,
			'allegiance' 		=> NULL,
			'primary_economy' 	=> NULL,
			'security' 			=> NULL,
			'state' 			=> NULL,
			'station' 			=> NULL
		];

		foreach (Yii::$app->request->get() as $k=>$v)
		{
			if (array_key_exists($k, $allowedSearchFilters))
				$allowedSearchFilters[$k] = $v;
		}

		$query = System::find();

		foreach ($allowedSearchFilters as $k=>$v)
		{
			if ($allowedSearchFilters[$k] != NULL)
				$query->andWhere([$k => $v]);
		}
		
		return ResponseBuilder::build($query, 'systems', Yii::$app->request->get('sort', 'id asc'));
	}

	/**
	 * Retrieve the details for a specific commodity
	 * @param integer $id
	 * @return array
	 */
	public function actionView($id=NULL)
	{
		if ($id === NULL)
			throw new HttpException(400, 'Missing ID parameter');

		$query = System::find()->where(['id' => $id]);
		return ResponseBuilder::build($query, 'systems', 'id asc');
	}
}