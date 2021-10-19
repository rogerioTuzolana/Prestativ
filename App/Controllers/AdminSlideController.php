<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassSlide;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminSlideController extends LayoutRender{
    private $slide;
    private $status;
    public function __construct(){
        $this->slide=new ClassSlide;
        $this->status='';
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($slide){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $estado="";
        foreach($slide as $port):
            $itens.= View::render("admin/dashboard/painel_slide/itemsSlide",[
            "id"=>$port['id'],
            "titulo"=>$port['titulo'],
            "descricao"=>$port['descricao'],
            "imagem"=>$port['imagem'],
            "URL"=>DIRPAGE
            ]);
            
        endforeach;
        
        return $itens;
    }
    public function index(){

        $content=View::render("admin/dashboard/painel_slide/slide",[
            "itens"=>self::items($this->slide->getSlide()),
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Slide",$content);  
    }
    public function add(){;
        //$user=$this->users->getUserById($id);
        $content=View::render("admin/dashboard/painel_slide/addSlide",[
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Adicionar Slide",$content);  
    }

    public function store(){
        $regist['imagem']='';
        $regist['titulo']=filter_input(INPUT_POST,'titulo',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;

        $file_upload=$this->verify_file();
        if(!empty($file_upload)):
        
            if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                $regist['imagem']=$file_upload['new_name'];
                $sli=$this->slide->create($regist);
                $this->status='<h5><br/>Slide adicionado!</h5>';
                echo "Imagem enviada com sucesso!";
                $this->add();
            else:
                $this->status='<h5><br/>Erro inesperado, o slide não foi adicionado!<br/>Verifique os campos.</h5>';
                $this->add();
            endif;
        else:
            $this->status='<h5><br/>Slide não adicionado!<br/>Verifique os campos e o tamanho da imagem.</h5>';
            $this->add();
        endif;
        
    }
    function verify_file(){
        $format_img=array('jpeg','png','jpg','gif');
        $getPost=filter_input(INPUT_GET,'post',FILTER_VALIDATE_BOOLEAN);
        $file_upload=[];
        if($_FILES && !empty($_FILES['imagem']['name'])):
            $extension=pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            if(in_array($extension, $format_img)):
                $folder='img/slide/';
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

        $slide=$this->slide->getSlideById($id);
        $content=View::render("admin/dashboard/painel_slide/editSlide",[
            "id"=>$slide[0]['id'],
            "titulo"=>$slide[0]['titulo'],
            "descricao"=>$slide[0]['descricao'],
            "imagem"=>$slide[0]['imagem'],
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Slide",$content);  
    }

    public function update($id){

        $regist['titulo']=filter_input(INPUT_POST,'titulo',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $file_upload=$this->verify_file();
        $verify_slide=$this->slide->getSlideById($id);
        if(!empty($file_upload)):
        
            if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                $regist['imagem']=$file_upload['new_name'];
                $sli=$this->slide->update($regist, $id);
                $this->status='<h5><br/>Slide editado!</h5>';
                //header('location:'.DIRPAGE."/dashboard-admin/slide");
                $this->edit($id);
            else:
                $this->status='<h5><br/>Erro inesperado, o slide não foi adicionado!<br/>Verifique os campos.</h5>';
                $this->edit($id);
            endif;
        else:
            if($file_upload==[]  && $verify_slide[0]['imagem']!=NULL):
                $regist['imagem']=$verify_slide[0]['imagem'];
                $team=$this->slide->update($regist, $id);
                $this->status='<h5><br/>Slide editado!</h5>';
                $this->edit($id);
            else:
                $this->status='<h5><br/>Slide não foi editado!<br/>Verifique os campos e o tamanho da imagem.</h5>';
                $this->edit($id);
            endif;
        endif;

    }
    public function delete($id){
        $this->slide->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/slide");
    }
}