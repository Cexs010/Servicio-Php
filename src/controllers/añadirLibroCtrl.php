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

            $title   = $datos['title'] ?? '';
            $author  = $datos['author'] ?? '';
            $date    = $datos['date'] ?? '';
            $cover   = $datos['cover_image_url'] ?? '';
            $file    = $datos['file_url'] ?? '';
            $rolId  = $datos['rol_id'] ?? '';
            $userId = $datos['user_id'] ?? null;

            $libro = new Libro();

            if (!$rolId) {
                throw new \Exception('Falta el id_user');
            }
            if (!$userId) {
                throw new \Exception('Falta el user_id');
            }

            if ($rolId == 2) {
                // Administrador
                $exito = $libro->crearLibro($title, $author, $date, $cover, $file, $rolId);
                $mensaje = $exito ? 'Libro agregado con éxito' : 'No se pudo agregar el libro';
                $status = $exito ? 201 : 400;
            } elseif ($rolId == 3) {
                // Colaborador crea solicitud de "crear"
                $datosSolicitud = json_encode([
                    'title' => $title,
                    'author' => $author,
                    'date' => $date,
                    'cover_image_url' => $cover,
                    'file_url' => $file
                ]);

                $exito = $libro->guardarSolicitud('crear', $datosSolicitud, $userId);
                $mensaje = $exito ? 'Solicitud de creación enviada para aprobación' : 'No se pudo registrar la solicitud';
                $status = $exito ? 202 : 400;
            } else {
                // Usuario no autorizado
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
