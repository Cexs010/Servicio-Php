<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Libro;
use Throwable;

class eliminarLibroCtrl
{
    public function eliminarLibro(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $datos = json_decode($body, true);

            $id = $datos['id'] ?? null;

            $libro = new Libro();
            $exito = $libro->eliminarLibro($id);

            if ($exito) {
                $mensaje = 'Libro eliminado con éxito';
                $status = 200;
            } else {
                $mensaje = 'No se pudo eliminar el libro';
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
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status ?? 200);
    }
}
