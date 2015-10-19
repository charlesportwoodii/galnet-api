<?php
$debug = false;
if (file_exists(__DIR__ . '/env.php'))
	$debug = require __DIR__ . '/env.php';

return [
	'debug' 	=> $debug,
	'galnet' 	=> [
		'url'		=> 'https://community.elitedangerous.com/',
		'startDate' => '06-JAN-3301'
	],
	'eddb' => [
		'archive' => 'http://eddb.io/archive/v3/'
	]
];
