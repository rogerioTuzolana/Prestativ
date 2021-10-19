<?php

namespace App\Session;

class SessionLogin{

    private static function init_session(){
        if(session_start()!= PHP_SESSION_ACTIVE):
            session_start();
        endif;

    }
    public static function session_login($dataUser){
        //Iniciar sessao login
        self::init_session();
        //Sessao do usuario
        $_SESSION['admin']['user']=[
            'id' => $dataUser['data']['id'],
            'nome' => $dataUser['data']['nome'],
            'email' => $dataUser['data']['email']
        ];
        return true;
    }

    public static function isLogged(){
        //Cria sessao login
        self::init_session();
        
        return isset($_SESSION['admin']['user']['id']);
    }

    public static function session_logout(){
        //Destroi sessao do usuario
        self::init_session();
        unset($_SESSION['admin']['user']);
        return true;
    }
}