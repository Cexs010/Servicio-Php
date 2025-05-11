<?php
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Cargar rutas
(require __DIR__ . '/routes/añadirLibro.php')($app);

$app->run();
