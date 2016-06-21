<?php

$app = require_once __DIR__ . '/../config/cli-config.php';

use Walgistics\Providers\LogisticResourceProvider;
use Silex\Application;

$app->get('/', function (Application $app) {
  return $app->json(['walgistics' => 'Welcome to Walmart Logistics API.']);
});

//Logistic
$app->register($logisticProvider = new LogisticResourceProvider());
$app->mount('/', $logisticProvider);

$app->run();
