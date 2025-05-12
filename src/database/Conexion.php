<?php

namespace App\Database;

use PDO;
use PDOException;

class Conexion {
    public static function obtenerDBConexion() {
        $host = 'shuttle.proxy.rlwy.net';
        $port = 37608;
        $db = 'railway';
        $user = 'root';
        $pass = 'KxmOZXKZTqLkAzqvAedoEjAPXYnShsyd';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass);
            return $pdo;
        } catch (PDOException $e) {
            exit('Error de conexión: ' . $e->getMessage());
        }
    }
}
