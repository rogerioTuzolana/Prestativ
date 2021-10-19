<?php

namespace App\Controllers;
use App\Session\SessionRecoverPassword;
use App\Model\ClassUser;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminForgotPassController extends ClassUser{
    
    public function __construct(){

        self::index();

    }
    public static function index(){
        $render=new LayoutRender;
        //Retorna a pagina section
        $content= View::render("admin/forgotpass",[
        "URL"=>DIRPAGE,
        "nome"=>"Rogerio Rona Joao Tuzolana",
        "discricao"=>"Programador",
        "servicos"=>"web-sites"
        ]);
        //Retorna a view da pagina
        echo $render->getLayout("admin/authlayout","AdminPTV | Esqueci password",$content);
    }

    public function exist_user(){
        $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_SPECIAL_CHARS);
        $dataUser=$this->getUser($email);
        if(empty($dataUser)):
            echo "<h3>Usuario inexistente!</h3>";
            return false;
        else:
            //cria sessao de login para o usuario
            SessionRecoverPassword::session_recover($dataUser);
            header('Location:'.DIRPAGE.'/dashboard-admin/recover-password');
        endif;
    }
}