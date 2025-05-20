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

    public function editarLibro($id, $title, $author, $date, $cover, $file)
    {
        $stmt = $this->pdo->prepare("UPDATE Books SET title = ?, author = ?, date = ?, cover_image_url = ?, file_url = ? WHERE id = ?");
        return $stmt->execute([$title, $author, $date, $cover, $file, $id]);
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
}
