<?php

namespace App\Http\Middleware;
use App\Session\SessionLogin;

class RequireAdminLogout{
    public function middleware_logged(){
        if(SessionLogin::isLogged()):
            //die('Usuario logado');
            header('Location:'.DIRPAGE.'/dashboard-admin'); 
        endif;
    }
}