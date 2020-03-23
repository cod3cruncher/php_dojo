<?php

namespace PHPDojo\Controllers;

use PHPDojo\Helpers;
use PHPDojo\Models\User;

class LoginController extends Controller
{
    public function index() : void{
        echo Helpers\TemplateRenderer::render('Login.simptemp', []
        );
    }

    public function login() : void {
        if(isset($_POST['username']) && isset($_POST['password'])) {
            $user = User::findByNamePasswd($_POST['username'], $_POST['password']);
            if($user != null) {
                $_SESSION['user'] = $user;
                header("Location: /todolist");
            }
            else {
                echo 'Could not login!!!';
            }
        }
    }

    public function logout() : void {
        session_destroy();
        header("Location: /");
    }

    public function isLoginNeeded(): bool {
        return false;
    }


}
