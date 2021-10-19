<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassTeam;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminTeamController extends LayoutRender{
    private $team;
    private $status;
    public function __construct(){
        $this->team=new ClassTeam;
        $this->status='';
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($team){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $estado="";
        foreach($team as $tm):
            $itens.= View::render("admin/dashboard/painel_team/itemsTeam",[
            "id"=>$tm['id'],
            "nome"=>$tm['nome'],
            "cargo"=>$tm['cargo'],
            "imagem"=>$tm['imagem'],
            "facebook"=>$tm['facebook'],
            "instagram"=>$tm['instagram'],
            "twitter"=>$tm['twitter'],
            "linkedin"=>$tm['linkedin'],
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public function index(){

        $content=View::render("admin/dashboard/painel_team/team",[
            "itens"=>self::items($this->team->getteam()),
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Team",$content);  
    }
    public function add(){

        $content=View::render("admin/dashboard/painel_team/addTeam",[
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Adicionar Membro",$content);  
    }

    public function store(){
        $regist['nome']=filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['cargo']=filter_input(INPUT_POST,'cargo',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $regist['facebook']=filter_input(INPUT_POST,'facebook',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['instagram']=filter_input(INPUT_POST,'instagram',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['twitter']=filter_input(INPUT_POST,'twitter',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;$regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['linkedin']=filter_input(INPUT_POST,'linkedin',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;

        if($regist['nome']!=NULL && $regist['cargo']!=NULL){
            $file_upload=$this->verify_file();
            if(!empty($file_upload)):
            
                if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                    $regist['imagem']=$file_upload['new_name'];
                    $team=$this->team->create($regist);
                    $this->status='<h5><br/>Membro adicionado!</h5>';
                    $this->add();
                else:
                    $this->status='<h5><br/>Erro inesperado, membro não foi adicionado!<br/>Verifique os campos.</h5>';
                    $this->add();
                endif;
            else:
                $this->status='<h5><br/>Membro não adicionado!<br/>Verifique os campos e o tamanho da imagem.</h5>';
                $this->add();
            endif;
        }else{
            $this->status='<h5><br/>Membro não adicionado!<br/>Verifique os campos.</h5>';
            $this->add();
        }

        
    }
    function verify_file(){
        $format_img=array('jpeg','png','jpg','gif');
        $getPost=filter_input(INPUT_GET,'post',FILTER_VALIDATE_BOOLEAN);
        $file_upload=[];
        if($_FILES && !empty($_FILES['imagem']['name'])):
            $extension=pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            if(in_array($extension, $format_img)):
                $folder='img/team/';
                $tmp=$_FILES['imagem']['tmp_name'];
                $new_name=uniqid().'.'.$extension;
                $file_upload=[
                    'tmp'=>$tmp,
                    'folder'=>$folder,
                    'new_name'=>$new_name
                ];
                //var_dump(['filesize'=>ini_get('upload_max_filesize'),'postsize'=>ini_get('post_max_size')]);
                return $file_upload;
            endif;
        else:
            if(!$getPost):
                return [];
            endif;       
        endif;
    }
    public function edit($id){

        $team=$this->team->getTeamById($id);
        $content=View::render("admin/dashboard/painel_team/editTeam",[
            "id"=>$team[0]['id'],
            "nome"=>$team[0]['nome'],
            "cargo"=>$team[0]['cargo'],
            "imagem"=>$team[0]['imagem'],
            "facebook"=>$team[0]['facebook'],
            "instagram"=>$team[0]['instagram'],
            "twitter"=>$team[0]['twitter'],
            "linkedin"=>$team[0]['linkedin'],
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Membro",$content);  
    }

    public function update($id){
        $regist['nome']=filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['cargo']=filter_input(INPUT_POST,'cargo',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $regist['facebook']=filter_input(INPUT_POST,'facebook',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['instagram']=filter_input(INPUT_POST,'instagram',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['twitter']=filter_input(INPUT_POST,'twitter',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;$regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['linkedin']=filter_input(INPUT_POST,'linkedin',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $verify_team=$this->team->getTeamById($id);

        if($regist['nome']!=NULL && $regist['cargo']!=NULL){
            //header('location:'.DIRPAGE."/dashboard-admin/team/edit/".$id);
            $file_upload=$this->verify_file();
            if($file_upload!=[]):
                if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                    $regist['imagem']=$file_upload['new_name'];
                    $team=$this->team->update($regist, $id);
                    $this->status='<h5><br/>Membro editado!</h5>';
                    $this->edit($id);
                else:
                    $this->status='<h5><br/>Erro inesperado, o membro não foi editado!<br/>Verifique os campos.</h5>';
                    $this->edit($id);
                endif;
            else: 
                if($file_upload==[]  && $verify_team[0]['imagem']!=NULL):
                    $regist['imagem']=$verify_team[0]['imagem'];
                    $team=$this->team->update($regist, $id);
                    $this->status='<h5><br/>Membro editado!</h5>';
                    $this->edit($id);
                else:
                    $this->status='<h5><br/>Membro não foi editado!<br/>Verifique os campos e o tamanho da imagem.</h5>';
                    $this->edit($id);
                endif;
            endif;
        }else{
            $this->status='<h5><br/>Membro não foi editado!<br/>Verifique os campos.</h5>';
            $this->edit($id);
        }
    }
    public function delete($id){
        $this->team->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/team");
    }
}