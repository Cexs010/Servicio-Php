<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Libro;
use Throwable;

class solicitudesCtrl
{
    // Obtener todas las solicitudes pendientes
    public function obtenerSolicitudesPendientes(Request $request, Response $response, $args)
    {
        try {
            $libro = new Libro();
            $solicitudes = $libro->obtenerSolicitudesPendientes();

            $payload = json_encode([
                'success' => true,
                'solicitudes' => $solicitudes
            ]);
            $status = 200;
        } catch (Throwable $e) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Error al obtener solicitudes: ' . $e->getMessage()
            ]);
            $status = 500;
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    // Aprobar o rechazar una solicitud
    public function procesarSolicitud(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $datos = json_decode($body, true);

            $idSolicitud = $datos['id'] ?? null;
            $accion = $datos['accion'] ?? ''; // aprobar o rechazar
            $rolId = $datos['rol_id'] ?? null;

            if ($rolId != 2) {
                throw new \Exception("No autorizado");
            }

            $libro = new Libro();
            if ($accion === 'aprobar') {
                $exito = $libro->aprobarSolicitud($idSolicitud);
                $mensaje = $exito ? 'Solicitud aprobada y aplicada' : 'No se pudo aplicar la solicitud';
                $status = $exito ? 200 : 400;
            } elseif ($accion === 'rechazar') {
                $exito = $libro->rechazarSolicitud($idSolicitud);
                $mensaje = $exito ? 'Solicitud rechazada' : 'No se pudo rechazar la solicitud';
                $status = $exito ? 200 : 400;
            } else {
                throw new \Exception("Acción inválida");
            }

            $payload = json_encode([
                'success' => $exito,
                'message' => $mensaje
            ]);
        } catch (Throwable $e) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
            $status = 500;
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
