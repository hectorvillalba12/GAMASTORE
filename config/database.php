<?php
class Database {
    public function connect() {
        $host   = $_ENV['DB_HOST']   ?? 'localhost';
        $dbname = $_ENV['DB_NAME']   ?? 'modelorelacional2';
        $user   = $_ENV['DB_USER']   ?? 'root';
        $pass   = $_ENV['DB_PASS']   ?? '';

        return new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }
}