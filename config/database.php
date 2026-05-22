<?php
class Database {
    public function connect() {
        return new PDO("mysql:host=localhost;dbname=modelorelacional2", "root", "3704219257");
    }
}
