<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassAbout;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminAboutController extends LayoutRender{
    private $about;
    private $status;
    private $op_route;
    private $op_route_info;
    public function __construct(){
        $this->about=new ClassAbout;
        $this->status='';
        $this->op_route='';
        $verify=new VerifyAuth;
        $verify->verify_user();
    }

    public function index(){
        $about="";
        if (empty($this->about->getAboutUs())) {
            $this->op_route='store';
        }else{
            $about=$this->about->getAboutUsById('1');
            $this->op_route='update/';
        }

        $content=View::render("admin/dashboard/painel_about/about",[
            "id"=>$about[0]['id']??'',
            "descricao"=>$about[0]['descricao']??'',
            "imagem"=>$about[0]['imagem']??'',
            "status"=>$this->status??'',
            "op_route"=>$this->op_route,
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Sobre Nós",$content);  
    }

    public function store(){
        $regist['id']='1';
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $file_upload=$this->verify_file();
        if($regist['descricao']!=NULL && $file_upload!=[]){
            if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                $regist['imagem']=$file_upload['new_name'];
                $about=$this->about->create($regist);
                $this->status='<h5><br/>Publicado!</h5>';
                $this->index();
            else:
                $this->status='<h5><br/>Erro inesperado!<br/>Verifique os campos.</h5>';
                $this->index();
            endif;
        }else{
            $this->status='<h5><br/>"Sobre Nós" não foi atualizado!<br/>Verifique os campos e o tamanho da imagem.</h5>';
            $this->index();
        }
    }

    function verify_file(){
        $format_img=array('jpeg','png','jpg','gif');
        $getPost=filter_input(INPUT_GET,'post',FILTER_VALIDATE_BOOLEAN);
        $file_upload=[];
        if($_FILES && !empty($_FILES['imagem']['name'])):
            $extension=pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            if(in_array($extension, $format_img)):
                $folder='img/';
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
    public function update($id){
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $verify_about=$this->about->getAboutUsById($id);
        if($regist['descricao']!=NULL){
            $file_upload=$this->verify_file();
            if($file_upload!=[]):
                if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):      
                    $regist['imagem']=$file_upload['new_name'];
                    $about=$this->about->update($regist, $id);
                    $this->status='<h5><br/>Atualizado!</h5>';
                    $this->index();
                else:             
                    $this->status='<h5><br/>Erro inesperado!<br/>Verifique os campos.</h5>';
                    $this->index();
                endif;
            else:
                if($file_upload==[]  && $verify_about[0]['imagem']!=NULL):
                    $regist['imagem']=$verify_about[0]['imagem'];
                    $about=$this->about->update($regist, $id);
                    $this->status='<h5><br/>Atualizado!</h5>';
                    $this->index();
                else:
                    $this->status='<h5><br/>"Sobre Nós" não foi atualizado!<br/>Verifique os campos e o tamanho da imagem.</h5>';
                    $this->index();
                endif;
            endif;
        }else{
            $this->status='<h5><br/>"Sobre Nós" não foi atualizado!<br/>Verifique os campos.</h5>';
            //$this->index();
        }
    }
    public function delete($id){
        $this->about->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/about");
    }
}