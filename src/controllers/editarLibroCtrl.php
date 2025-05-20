<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Libro;
use Throwable;

class editarLibroCtrl
{
    public function editarLibro(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $datos = json_decode($body, true);

            $id     = $datos['id'] ?? null;
            $title  = $datos['title'] ?? '';
            $author = $datos['author'] ?? '';
            $date   = $datos['date'] ?? '';
            $rolId  = $datos['rol_id'] ?? null;

            $libro = new Libro();

            if (!$rolId) {
                throw new \Exception('Falta el rol_id');
            }

            if ($rolId == 2) {
                $exito = $libro->editarLibro($id, $title, $author, $date);
                $mensaje = $exito ? 'Libro editado con éxito' : 'No se pudo editar el libro';
                $status = $exito ? 200 : 400;
            } elseif ($rolId == 3) {
                $exito = false;
                $mensaje = 'Los colaboradores no pueden editar libros';
                $status = 403;
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
