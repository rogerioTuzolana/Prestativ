<?php

namespace App\Controllers;
//use Src\Classes\RenderAdmin;
//use Src\Interfaces\InterfaceView;
use App\Model\ClassValidation;
use App\Session\SessionLogin;
use App\Model\ClassUser;
use App\Http\Middleware\RequireAdminLogout;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminLoginController extends LayoutRender{
    private $user;
    private $middleware;

    public function __construct(){
        
        RequireAdminLogout::middleware_logged();
        $this->user = new ClassUser;
    }
    public static function index(){
        //Retorna a pagina section
        $content= View::render("admin/login",[
        "URL"=>DIRPAGE,
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/authlayout","AdminPTV | Log in",$content);
    }

    public function login(){
        //Verifica senha do usuario
        if(empty($_POST['email']) && empty($_POST['password'])){
            header('Location:'.DIRPAGE.'/dashboard-admin/login');
            //echo "Preenche os campos!";
            return ;
        }

        $email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)??'';

        $pass=filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);

        $dataUser=$this->user->getUser($email);
        if(empty($dataUser)):
            header('Location:'.DIRPAGE.'/dashboard-admin/login');
            //echo "Usuário ou Senha inválida!";
            return;
        else:
            if(!password_verify($pass, $dataUser['data']['password'])):
                header('Location:'.DIRPAGE.'/dashboard-admin/login');
                //echo "Usuário ou Senha inválida!";
                return;
            endif;
        endif;
        //cria sessao de login para o usuario
        SessionLogin::session_login($dataUser);
        header('Location:'.DIRPAGE.'/dashboard-admin');
        //print_r($_SESSION);
        
    }
    public function logout(){
        //destroi sessao de login
        SessionLogin::session_logout();
        header('Location:'.DIRPAGE.'/dashboard-admin/login');
    }
}