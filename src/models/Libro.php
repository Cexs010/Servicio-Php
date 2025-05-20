<?php

namespace App\Models;

use App\Database\Conexion;

class Libro
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::obtenerDBConexion();
    }

    public function crearLibro($title, $author, $date, $cover, $file)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Books (title, author, date, cover_image_url, file_url) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $date, $cover, $file]);
    }

    public function editarLibro($id, $title, $author, $date)
    {
        $stmt = $this->pdo->prepare("UPDATE Books SET title = ?, author = ?, date = ? WHERE id = ?");
        return $stmt->execute([$title, $author, $date, $id]);
    }

    public function eliminarLibro($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Books WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerLibros($search = '')
    {
        if (!empty($search)) {
            // Buscar por título o autor si hay búsqueda
            $stmt = $this->pdo->prepare("SELECT * FROM Books WHERE title LIKE :search OR author LIKE :search");
            $stmt->execute(['search' => "%$search%"]);
        } else {
            // Obtener todos si no hay búsqueda
            $stmt = $this->pdo->prepare("SELECT * FROM Books");
            $stmt->execute();
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function guardarSolicitud($tipoAccion, $datosJson, $userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO SolicitudesColaborador (tipo_accion, datos, id_user) VALUES (?, ?, ?)");
        return $stmt->execute([$tipoAccion, $datosJson, $userId ]);
    }

    public function obtenerSolicitudesPendientes()
    {
        $stmt = $this->pdo->query("SELECT * FROM SolicitudesColaborador WHERE estado = 'pendiente'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

public function aprobarSolicitud($idSolicitud)
{
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM SolicitudesColaborador WHERE id = ?");
        $stmt->execute([$idSolicitud]);
        $solicitud = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$solicitud) {
            throw new \Exception("Solicitud no encontrada");
        }

        $datos = json_decode($solicitud['datos'], true);
        $accion = $solicitud['tipo_accion'];

        if ($accion === 'crear') {
            $stmt = $this->pdo->prepare("INSERT INTO Books (title, author, date, cover_image_url, file_url) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt->execute([$datos['title'], $datos['author'], $datos['date'], $datos['cover_image_url'], $datos['file_url']])) {
                $errorInfo = $stmt->errorInfo();
                throw new \Exception("Error al insertar libro: " . $errorInfo[2]);
            }
        } elseif ($accion === 'editar') {
            $stmt = $this->pdo->prepare("UPDATE Books SET title = ?, author = ?, date = ? WHERE id = ?");
            if (!$stmt->execute([$datos['title'], $datos['author'], $datos['date'], $datos['id']])) {
                $errorInfo = $stmt->errorInfo();
                throw new \Exception("Error al actualizar libro: " . $errorInfo[2]);
            }
        } elseif ($accion === 'eliminar') {
            $stmt = $this->pdo->prepare("DELETE FROM Books WHERE id = ?");
            if (!$stmt->execute([$datos['id']])) {
                $errorInfo = $stmt->errorInfo();
                throw new \Exception("Error al eliminar libro: " . $errorInfo[2]);
            }
        } else {
            throw new \Exception("Acción desconocida");
        }

        $update = $this->pdo->prepare("UPDATE SolicitudesColaborador SET estado = 'aprobado' WHERE id = ?");
        if (!$update->execute([$idSolicitud])) {
            $errorInfo = $update->errorInfo();
            throw new \Exception("Error al actualizar estado de solicitud: " . $errorInfo[2]);
        }

        return true;

    } catch (\Exception $e) {
        error_log("aprobarSolicitud error: " . $e->getMessage());
        return false;
    }
}


    public function rechazarSolicitud($idSolicitud)
    {
        $stmt = $this->pdo->prepare("UPDATE SolicitudesColaborador SET estado = 'rechazado' WHERE id = ?");
        return $stmt->execute([$idSolicitud]);
    }
}
