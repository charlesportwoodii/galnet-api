<?php

namespace app\commands;

use app\models\News;
use app\models\Commodity;
use app\models\CommodityCategory;
use app\models\System;

use PHPHtmlParser\Dom;
use Curl\Curl;
use JsonStreamingParser\Parser;
use yii\helpers\Console;
use yii\helpers\Json;
use Yii;

/**
 * Console command for importing data from Galnet
 */
class ImportController extends \yii\console\Controller
{

	/**
	 * Set the default action for the controller
	 * @var string $defaultAction
	 */
	public $defaultAction = 'news';

	/**
	 * Imports commodity information from EDDB
	 */
	public function actionCommodities()
	{
		$eddbApi = Yii::$app->params['eddb']['archive'] . 'commodities.json';

		$curl = new Curl;
		$curl->get($eddbApi);

		if ($curl->error)
			throw new \yii\base\Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);

		// Iterate through all the categories via the curl response and insert them into the database
		foreach ($curl->response as $k => $obj)
		{
			$this->stdOut("Importing: ");
			$this->stdOut("{$obj->name}\r", Console::BOLD);
			$category = CommodityCategory::find()->where(['id' => $obj->category_id])->one();
			if ($category === NULL)
			{
				$this->stdOut('Importing new category: ' . $obj->category->name . "\n");
				$category = new CommodityCategory;
				$category->id = $obj->category_id;
			}

			$category->name = $obj->category->name;
			$category->save();

			// Import the commodity
			$commodity = Commodity::find()->where(['id' => $obj->id])->one();

			if ($commodity === NULL)
			{
				$this->stdOut('Importing new commodity: ' . $obj->name . "\n");
				$commodity = new Commodity;
				$commodity->id = $obj->id;
				$commodity->name = $obj->name;
			}

			$commodity->average_price = $obj->average_price;
			$commodity->category_id = $obj->category_id;
			$commodity->save();
		}

		$this->stdOut("\n\r");
	}

	/**
	 * Imports system information from EDDB
	 */
	public function actionSystems()
	{
		$eddbApi = Yii::$app->params['eddb']['archive'] . 'systems.json';

		$time = date('d-m-y_h');
		$file = Yii::getAlias('@app') . '/runtime/systems_' . $time . '.json';

		// Download the data from EDDB if the data that we have is out of date
		if (!file_exists($file))
		{
			$this->stdOut('Systems data from EDDB is out of date, fetching RAW JSON from EDDB');
			$curl = new Curl;
			$curl = new Curl();
			$curl->setOpt(CURLOPT_ENCODING , 'gzip');
			$curl->download($eddbApi, $file);
		}

		$this->importJsonData($file, 'systems');
	}

	/**
	 * Imports stations information from EDDB
	 */
	public function actionStations()
	{
		$eddbApi = Yii::$app->params['eddb']['archive'] . 'stations.json';

		$time = date('d-m-y_h');
		$file = Yii::getAlias('@app') . '/runtime/stations' . $time . '.json';

		// Download the data from EDDB if the data that we have is out of date
		if (!file_exists($file))
		{
			$this->stdOut('Systems data from EDDB is out of date, fetching RAW JSON from EDDB');
			$curl = new Curl;
			$curl = new Curl();
			$curl->setOpt(CURLOPT_ENCODING , 'gzip');
			$curl->download($eddbApi, $file);
		}

		$this->importJsonData($file, 'stations');
	}

	/**
	 * Imports data from Galnet Community website
	 *
	 * By default, data will be imported only for "today" in Galnet time
	 * Optionally, data can be imported from the first Galnet post on Yii::$app->params['galnet']['startDate']
	 * until $from, which is today
	 *
	 * @param string $to 	When to begin date
	 * @param string $from 	When to end date
	 * @return void
	 */
	public function actionNews($to=NULL, $from=NULL)
	{
		$year = (int)date('Y') + 1286;
		if ($to === 'start')
			$to = Yii::$app->params['galnet']['startDate'];
		else if ($to === NULL)
			$to = date('d-M-' . $year);

		if ($from === NULL)
			$from = date('d-M-' . $year);

		$origin = $to;

		// Iterate over all the pages to fetch data
		while(true)
		{
			$this->stdOut('Importing news on: ');
			$this->stdOut("$origin\n", Console::BOLD);

			$dom = new Dom;
			$dom->loadFromUrl(Yii::$app->params['galnet']['url'] . '/galnet/' . $origin);

			$html = $dom->find('h3.galnetNewsArticleTitle a');

			$count = count($html);

			for ($i = 0; $i < $count; $i++)
				$this->importNewsEntry($html[$i], $origin);

			// Exit the loop if we've "today"
			if ($origin == $from)
				break;

			$origin = date('d-M-' . $year, strtotime($origin . '+1 day'));
		}
	}

	/**
	 * Imports a specific news entry
	 * @param PHPHtmlParser\Dom $html
	 * @return boolean|null
	 */
	private function importNewsEntry($html, $origin)
	{
		$dom = new Dom;
		$uri = $html->getAttribute('href');
		$uid = str_replace('/galnet/uid/', '', $uri);

		$count = (new \yii\db\Query())
			->from('news')
			->where(['uid' => $uid])
			->count();

		if ((int)$count != 0)
		{
			$this->stdOut("    - $uid :: Already Imported...\n");
			return;
		}

		$dom->loadFromUrl(Yii::$app->params['galnet']['url'] . $uri);
		
		$title = trim(strip_tags($dom->find('h3.galnetNewsArticleTitle a')[0]->innerHtml));
		$content = trim(strip_tags(str_replace('<br /><br /> ', "\n", $dom->find('div.article p')[0]->innerHtml)));

		// Early Galnet posts are empty, so grab the first line from the article
		if (empty($title))
			$title = strtok($content, "\n");

		$news = new News;
		$news->attributes = [
			'uid' => $uid,
			'title' => $title,
			'content' => $content,
			'created_at' => time(),
			'updated_at' => time(),
			'published_at_native' => strtotime($origin),
			'published_at' => strtotime($origin . "-1286 years")
		];

		$this->stdOut("    - $uid\n");
		$news->save();
	}

	/**
	 * Imports large JSON object files
	 * @param string $file
	 */
	private function importJsonData($file=NULL, $type=NULL)
	{
		if ($type === NULL || $file === NULL)
			throw new \yii\base\Exception('Missing file or type');

		$stream = fopen($file, 'r');
		$objectParser = $this->getObjectParser($type);
		try 
		{  
			$parser = (new \JsonStreamingParser_Parser(
				$stream, 
				$objectParser
			))->parse();
		} catch (Exception $e) {
			fclose($stream);
			throw new \yii\base\Exception($e->getMessage());
		}

		fclose($stream);
	}

	/**
	 * The object listener that actually parses the systems JSON Object
	 * @return ObjectListener
	 */
	private function getObjectParser($type)
	{
		if ($type === 'systems')
			$parser = $this->getSystemsObjectParser();
		else if ($type == 'stations')
			$parser = $this->getStationsObjectParser();
		else
			throw new \yii\base\Exception('Invalid import parser type');

		return (new \ObjectListener(
			$parser, 
			function($obj) {})
		);
	}

	/**
	 * The object parser for EDDB stations
	 * @return function
	 */
	private function getStationsObjectParser()
	{
		return function($obj) {
			$this->stdOut('Importing station: ');
			$this->stdOut("{$obj[0]['name']}\r", Console::BOLD);
		};
	}
	/**
	 * The object parser for EDDB systems
	 * @return function
	 */
	private function getSystemsObjectParser()
	{
		return function($obj) {
			$this->stdOut('Importing system: ');
			$this->stdOut("{$obj[0]['name']}\r", Console::BOLD);

			$model = System::find()->where(['id' => $obj[0]['id']])->one();

			if ($model === NULL)
				$model = new System;

			foreach ($obj[0] as $name=>$value)
			{
				if ($model->hasAttribute($name))
					$model->$name = $value;
			}

			$model->save();
		};
	}
}