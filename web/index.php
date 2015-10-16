<?php
// Define our application_env variable as provided by nginx/apache
if (!defined('APPLICATION_ENV'))
	define('APPLICATION_ENV', 'main');

require __DIR__ . '/../vendor/autoload.php';
$env = require __DIR__ . '/../config/params.php';

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', $env['debug']);
defined('YII_ENV') or define('YII_ENV', YII_DEBUG ? 'dev' : 'prod');

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config))->run();
