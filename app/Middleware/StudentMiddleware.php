<?php

namespace App\Middleware;

class StudentMiddleware{

    public function execute() {
        if (!isAdmin() && !isStudent()) {
            setLayout('error');
            echo view('403');
            die;
        }        
    }
}