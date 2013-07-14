<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application(array(
	'debug' => true
));

$config = require __DIR__ . '/../config.php';
foreach ($config as $key => $value)
{
	$app[$key] = $value;
}

$app['db'] = $app->share(function() use ($app) {
	return new \PDO(
		'mysql:host=' . $app['db_host'] . ';dbname=' . $app['db_name'],
		$app['db_user'],
		$app['db_pass']
	);
});

$app['mustache'] = $app->share(function() {
	return new \Mustache_Engine(array(
		'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__ . '/templates', array('extension' => 'mustache')),
	));
});

$app->get('/', function() use ($app) {
	$template = $app['mustache']->loadTemplate('tweet');
	return $template->render();
});

$app->run();
