<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Hellofresh\App(new Hellofresh\Config('1.0', 'hellofresh', false));

$app->registerService(new \Hellofresh\Service\Database\Service(), new \Hellofresh\Service\Database\Config());
$app->registerController('\app\Controller\Recipes');

$app->run();
