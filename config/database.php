<?php
class Database {
    public function connect() {
        $host   = Env::get('DB_HOST', 'localhost');
        $dbname = Env::get('DB_NAME', 'modelorelacional2');
        $user   = Env::get('DB_USER', 'root');
        $pass   = Env::get('DB_PASS', '');

        return new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }
}