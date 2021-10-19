<?php

namespace App\Controllers;
use App\Session\SessionRecoverPassword;
use App\Model\ClassRecoverPassword;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminRecoverPassController extends ClassRecoverPassword{
    
    public function __construct(){

        self::index();
    }
    public static function index(){
        $render=new LayoutRender;
        //Retorna a pagina section
        $content= View::render("admin/recoverpass",[
        "URL"=>DIRPAGE,
        "nome"=>"Rogerio Rona Joao Tuzolana",
        "discricao"=>"Programador",
        "servicos"=>"web-sites"
        ]);
        //Retorna a view da pagina
        echo $render->getLayout("admin/authlayout","AdminPTV | Recuperar password",$content);
    }
    public function update(){
        
        if(!empty($_POST['password'])){
            $password=filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);
            $confirma_password=filter_input(INPUT_POST,'confirmaPassword',FILTER_SANITIZE_SPECIAL_CHARS);
            if($password==$confirma_password):
                $this->update_password($password);
                echo "<h5><br/>Password alterado com sucesso!<h5/>";
            else:
                echo "<h5><br/>Falha: Verifique a password!<h5/>";
            endif;      
        }else{
            echo "<h5><br/>Falha: Verifique a password!<h5/>";
        }
    }
}