<?php

require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\añadirLibroCtrl;

$app = AppFactory::create();
$app->setBasePath('/Servicio-Php');

// Ruta para añadir un libro
$app->post('/AñadirLibro', function (Request $request, Response $response) {
    $controller = new añadirLibroCtrl();
    return $controller->añadirLibro($request, $response, []);
});

$app->run();
