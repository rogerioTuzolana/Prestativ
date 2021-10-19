<?php

namespace App\Model;
use App\Model\ClassConnection;
use App\Model\ClassUser;
use App\Model\ClassPassword;

abstract class ClassRecoverPassword extends ClassConnection{
    private static function init_session(){
        if(session_start()!= PHP_SESSION_ACTIVE):
            session_start();
        endif;

    }
    //verifica se foi criada sessao de recuperacao da senha do user
    public function verify_session_recover_password(){
        self::init_session();
        if(!isset($_SESSION['recover'])){
            session_destroy();
            header('Location:'.DIRPAGE.'/dashboard-admin/forgot-password');    
            exit;
        }else{
            return $_SESSION['recover']['email']; 
        }
    }
    public function update_password($password){
        $password_hash=new ClassPassword;
        $sql="UPDATE users SET password = ? WHERE email = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $password_hash->passwordHash($password));
        $update->bindValue(2, $this->verify_session_recover_password());
        $update->execute();
    }
}