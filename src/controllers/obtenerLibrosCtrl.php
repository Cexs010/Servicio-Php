<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Libro;
use Throwable;

class obtenerLibrosCtrl
{
    public function obtenerLibros(Request $request, Response $response, $args)
    {
        try {
            $libro = new Libro();
            $libros = $libro->obtenerLibros();

            if ($libros) {
                $mensaje = 'Libros obtenidos con éxito';
                $status = 200;
            } else {
                $mensaje = 'No se encontraron libros';
                $status = 404;
            }

            $payload = json_encode([
                'success' => true,
                'message' => $mensaje,
                'data' => $libros
            ]);
        } catch (Throwable $e) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
            $status = 500;
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status ?? 200);
    }
}