<?php

namespace App\Model;

class VerifyAuth {
    private static function init_session(){
        if(session_start()!= PHP_SESSION_ACTIVE):
            session_start();
        endif;

    }
    
    public function verify_user(){
        self::init_session();
        if(!isset($_SESSION['admin']['user'])){
            session_destroy();
            header('Location:'.DIRPAGE.'/dashboard-admin/login');    
            exit;
        }
    }
    
}
