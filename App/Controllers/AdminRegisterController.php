<?php

namespace App\Controllers;

use App\Model\Register;
use App\Model\ClassRegister;
use App\Model\ClassPassword;
use App\Model\VerifyAuth;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminRegisterController extends ClassRegister{

    private $register;
    private $passHash;
    public function __construct(){
        $verify=new VerifyAuth;
        $verify->verify_user();
        $this->register= new Register;
        $this->passHash=new ClassPassword;
    }
    public static function index(){
        $render=new LayoutRender;
        //Retorna a pagina padrao(Layout)
        $content= View::render("admin/register",[
        "URL"=>DIRPAGE,
        "nome"=>"Rogerio Rona Joao Tuzolana",
        "discricao"=>"Programador",
        ]);
        //Retorna a view da pagina
        echo $render->getLayout("admin/authlayout","AdminPTV | Register",$content);
    }
    public function filtrar_dados(){
        if(isset($_POST['nome'])){
            $this->register->setNome(filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS));
        }
        if(isset($_POST['email'])){
            $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
            if(filter_var($email,FILTER_VALIDATE_EMAIL)): 
                $this->register->setEmail($email);   
            endif;
            
        }
        if(isset($_POST['nivel'])){
            $this->register->setNivel(filter_input(INPUT_POST,'nivel',FILTER_SANITIZE_SPECIAL_CHARS));
            
        }else{$this->register->setNivel("");}
        if(isset($_POST['imagem'])){
            $this->register->setImagem(filter_input(INPUT_POST,'imagem',FILTER_SANITIZE_SPECIAL_CHARS));

        }{$this->register->setImagem("");}

        if(isset($_POST['password'])){
            $pass=filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);
            $confirma_pass=filter_input(INPUT_POST,'confirmaPassword',FILTER_SANITIZE_SPECIAL_CHARS);
            if($pass==$confirma_pass):
                $this->register->setPassword($this->passHash->passwordHash($pass));
            else:
                //echo "<h5><br/>Falha no registo!</h5><br><h6>Verifique a password<h6/>";
                $pass='';
            endif;
        }
    }
    public function verify_email(){
        $sql="SELECT * FROM users  WHERE (email = ?)";
        $select=parent::connect_db()->prepare($sql);
        $select->bindValue(1, $this->register->getEmail());
        $select->execute();
        if($select->rowCount() > 0):
            return true;
        else:
            return false;
        endif;
    }
    //metodo para inserir dados no user.
    public function store(){
        $this->filtrar_dados();
        if($this->verify_email()==true){
            header('Location:'.DIRPAGE.'/dashboard-admin/login');
        }else{
            if($this->register->getNome()=='' || $this->register->getEmail()=='' || $this->register->getPassword()==''){
                echo "<h5><br/>Falha no registo! Verifique os dados.<h5/>";
            }else{
                $this->create($this->register);
                echo "<h5><br/>Cadastrado com sucesso!<h5/>";
            }
        }
        $this->index();
    }

    
}