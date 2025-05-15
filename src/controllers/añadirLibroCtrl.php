<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Libro;
use Throwable;

class añadirLibroCtrl
{
    public function añadirLibro(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $datos = json_decode($body, true);

            $title = $datos['title'] ?? '';
            $author = $datos['author'] ?? '';
            $date = $datos['date'] ?? '';
            $cover = $datos['cover_image_url'] ?? '';
            $file = $datos['file_url'] ?? '';

            $libro = new Libro();
            $exito = $libro->crearLibro($title, $author, $date, $cover, $file);

            if ($exito) {
                $mensaje = 'Libro agregado con exito';
                $status = 201;
            } else {
                $mensaje = 'No se pudo agregar el libro';
                $status = 400;
            }

            $payload = json_encode([
                'success' => $exito,
                'message' => $mensaje
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
