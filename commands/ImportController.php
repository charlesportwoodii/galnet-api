<?php

namespace app\commands;

use app\models\News;

use PHPHtmlParser\Dom;

use yii\helpers\HtmlPurifier;
use yii\helpers\Console;
use Yii;

/**
 * Console command for importing data from Galnet
 */
class ImportController extends \yii\console\Controller
{
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
	public function actionIndex($to=NULL, $from=NULL)
	{
		$year = (int)date('Y') + 1286;
		if ($to === 'start')
			$to = Yii::$app->params['galnet']['startDate'];
		else if ($to == NULL)
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

			for ($i = 0; $i < count($html); $i++)
			{
				$uri = $html[$i]->getAttribute('href');
				$uid = str_replace('/galnet/uid/', '', $uri);

				$count = (new \yii\db\Query())
					->from('news')
					->where(['uid' => $uid])
					->count();

				if ((int)$count != 0)
				{
					$this->stdOut("    - $uid :: Already Imported...\n");
					continue;
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

			// Exit the loop if we've "today"
			if ($origin == $from)
				break;

			$origin = date('d-M-' . $year, strtotime($origin . '+1 day'));
		}
	}	
}