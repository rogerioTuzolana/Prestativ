<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassBlog;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminBlogController extends LayoutRender{
    private $blog;
    private $status;
    public function __construct(){
        $this->blog=new ClassBlog;
        $this->status='';
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($blog){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $estado="";
        foreach($blog as $blg):
            $itens.= View::render("admin/dashboard/painel_blog/itemsBlog",[
            "id"=>$blg['id'],
            "titulo"=>$blg['titulo'],
            "descricao"=>$blg['descricao'],
            "imagem"=>$blg['imagem'],
            "time"=>$blg['time'],
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public function index(){

        $content=View::render("admin/dashboard/painel_blog/blog",[
            "itens"=>self::items($this->blog->getBlog()),
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Publicações",$content);  
    }
    public function add(){

        $content=View::render("admin/dashboard/painel_blog/addBlog",[
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Adicionar Serviço",$content);  
    }

    public function store(){
        $regist['titulo']=filter_input(INPUT_POST,'titulo',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        if($regist['titulo']!=NULL && $regist['descricao']!=NULL){
            $file_upload=$this->verify_file();
            if(!empty($file_upload)):
                if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                    $regist['imagem']=$file_upload['new_name'];
                    $blog=$this->blog->create($regist);
                    $this->status='<h5><br/>Artigo publicado!</h5>';
                    $this->add();
                else:
                    $this->status='<h5><br/>Erro inesperado, artigo não foi publicado!<br/>Verifique os campos.</h5>';
                    $this->add();
                endif;
            else:
                $blog=$this->blog->create($regist);
                $this->status='<h5><br/>Artigo publicado!</h5>';
                $this->add();
            endif;
        }else{
            $this->status='<h5><br/>Artigo não foi publicado!<br/>Verifique os campos.</h5>';
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
                $folder='img/blog/';
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

        $blog=$this->blog->getBlogById($id);
        $content=View::render("admin/dashboard/painel_blog/editBlog",[
            "id"=>$blog[0]['id'],
            "titulo"=>$blog[0]['titulo'],
            "descricao"=>$blog[0]['descricao'],
            "imagem"=>$blog[0]['imagem'],
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Publição",$content);  
    }

    public function update($id){
        $regist['titulo']=filter_input(INPUT_POST,'titulo',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        if($regist['titulo']!=NULL && $regist['descricao']!=NULL){
            $file_upload=$this->verify_file();
            if(!empty($file_upload)):
                if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                    $regist['imagem']=$file_upload['new_name'];
                    $blog=$this->blog->update($regist, $id);
                    $this->status='<h5><br/>Artigo editado!</h5>';
                    $this->edit($id);
                    //header('location:'.DIRPAGE."/dashboard-admin/portfolio");
                else:
                    $this->status='<h5><br/>Erro inesperado, o artigo não foi editado!<br/>Verifique os campos.</h5>';
                    $this->edit($id);
                endif;
            else:
                $blog=$this->blog->update($regist, $id);
                $this->status='<h5><br/>Artigo editado!</h5>';
                $this->edit($id);
            endif;
        }else{
            $this->status='<h5><br/>Artigo não foi editado!<br/>Verifique os campos.</h5>';
            $this->edit($id);
        }
    }
    public function delete($id){
        $this->blog->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/blog");
    }
}