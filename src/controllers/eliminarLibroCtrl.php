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

            $id    = $datos['id'] ?? null;
            $rolId = $datos['rol_id'] ?? null;

            $libro = new Libro();

            if (!$rolId) {
                throw new \Exception('Falta el rol_id');
            }

            if ($rolId == 2) {
                $exito = $libro->eliminarLibro($id);
                $mensaje = $exito ? 'Libro eliminado con éxito' : 'No se pudo eliminar el libro';
                $status = $exito ? 200 : 400;
            } elseif ($rolId == 3) {
                $exito = false;
                $mensaje = 'Lógica para colaboradores aún no implementada';
                $status = 501;
            } else {
                $exito = false;
                $mensaje = 'Usuario no autorizado para esta acción';
                $status = 403;
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
