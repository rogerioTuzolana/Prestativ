<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassUser;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminUsersController extends LayoutRender{
    private $users;
    public function __construct(){
        $this->users=new ClassUser;
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($users){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $nivel="";
        foreach($users as $user):
            if($user['nivel']=='1'):
                $nivel='admin';
            else:
                $nivel='user';
            endif;
            $itens.= View::render("admin/dashboard/painel_users/itemsUsers",[
            "id"=>$user['id'],
            "nome"=>$user['nome'],
            "email"=>$user['email'],
            "nivel"=>$nivel,
            "data"=>$user['time_stamp'],
            "URL"=>DIRPAGE
            ]);
            
        endforeach;
        
        return $itens;
    }
    public function index(){

        $content=View::render("admin/dashboard/painel_users/users",[
            "itens"=>self::items($this->users->getUsers()),
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Utilizadores",$content);  
    }

    public function edit($id){
        $nivel=[];
        $user=$this->users->getUserById($id);
        if($user[0]['nivel']=='1'):
            $nivel['admin']='selected';
            $nivel['user']='';
        else:
            $nivel['user']='selected';
            $nivel['admin']='';
        endif;
        $content=View::render("admin/dashboard/painel_users/edituser",[
            "id"=>$user[0]['id'],
            "nome"=>$user[0]['nome'],
            "email"=>$user[0]['email'],
            "data"=>$user[0]['time_stamp'],
            "nivel_admin"=>$nivel['admin'],
            "nivel_user"=>$nivel['user'],
            "URL"=>DIRPAGE
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Utilizador",$content);  
    }
    public function update($id){
        $nome=filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL)??NULL;
        $nivel=filter_input(INPUT_POST,'nivel',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        if($nome!=NULL && filter_var($email,FILTER_VALIDATE_EMAIL) && $nivel!=NULL){
            if($nivel=='admin'):
                $registo['nivel']='1';
            else:
                $registo['nivel']='2';
            endif;
            $registo['nome']=$nome;
            $registo['email']=$email;
            $user=$this->users->update($registo, $id);
            header('location:'.DIRPAGE."/dashboard-admin/users");
        }else{
            header('location:'.DIRPAGE."/dashboard-admin/users/edit/{$id}");
        }
    }
    public function delete($id){
        $this->users->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/users");
    }
}