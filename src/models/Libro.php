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
}
