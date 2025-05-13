<?php

require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\añadirLibroCtrl;
use App\Controllers\editarLibroCtrl;
use App\Controllers\eliminarLibroCtrl;

$app = AppFactory::create();
$app->setBasePath('/phpservicio/Servicio-Php');

// Ruta para añadir un libro
$app->post('/AñadirLibro', function (Request $request, Response $response) {
    $controller = new añadirLibroCtrl();
    return $controller->añadirLibro($request, $response, []);
});
// Editar un libro
$app->put('/EditarLibro', function (Request $request, Response $response) {
    $controller = new editarLibroCtrl();
    return $controller->editarLibro($request, $response, []);
});

// Eliminar un libro
$app->delete('/EliminarLibro', function (Request $request, Response $response) {
    $controller = new eliminarLibroCtrl();
    return $controller->eliminarLibro($request, $response, []);
});
$app->run();
