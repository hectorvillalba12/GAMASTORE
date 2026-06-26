<?php

class DashboardController {

    public function index() {
        Auth::verificar();
        require __DIR__ . "/../views/dashboard/index.php";
    }
}