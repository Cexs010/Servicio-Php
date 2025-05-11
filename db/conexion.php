<?php
function getDB() {
    $host = 'tu_host';
    $db = 'nombre_db';
    $user = 'usuario';
    $pass = 'password';

    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $user, $pass);
        return $pdo;
    } catch (PDOException $e) {
        exit('Error de conexión: ' . $e->getMessage());
    }
}
