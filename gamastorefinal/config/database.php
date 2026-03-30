<?php
class Database {
    public function connect() {
        return new PDO("mysql:host=localhost;dbname=modelorelacional", "root", "3704219257");
    }
}
