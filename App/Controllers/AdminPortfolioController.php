<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassPortfolio;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminPortfolioController extends LayoutRender{
    private $portfolio;
    private $status;
    public function __construct(){
        $this->portfolio=new ClassPortfolio;
        $this->status='';
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($portfolio){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $estado="";
        foreach($portfolio as $port):
            $itens.= View::render("admin/dashboard/painel_portfolio/itemsPortfolio",[
            "id"=>$port['id'],
            "categoria"=>$port['categoria'],
            "descricao"=>$port['descricao'],
            "imagem"=>$port['imagem'],
            "URL"=>DIRPAGE
            ]);
            
        endforeach;
        
        return $itens;
    }
    public function index(){

        $content=View::render("admin/dashboard/painel_portfolio/portfolio",[
            "itens"=>self::items($this->portfolio->getPortfolio()),
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Portfolio",$content);  
    }
    public function add(){;
        //$user=$this->users->getUserById($id);
        $content=View::render("admin/dashboard/painel_portfolio/addPortfolio",[
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Adicionar Portfolio",$content);  
    }

    public function store(){
        $regist['categoria']=filter_input(INPUT_POST,'categoria',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $file_upload=$this->verify_file();
        if(!empty($file_upload)):
        
            if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                $regist['imagem']=$file_upload['new_name'];
                $port=$this->portfolio->create($regist);
                $this->status='<h5><br/>Portfolio adicionado!</h5>';
                $this->add();
            else:
                $this->status='<h5><br/>Erro inesperado, portfolio não foi adicionado!<br/>Verifique os campos.</h5>';
                $this->add();
            endif;
        else:
            $this->status='<h5><br/>Portfolio não adicionado!<br/>Verifique os campos.</h5>';
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
                $folder='img/portfolio/';
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

        $port=$this->portfolio->getPortfolioById($id);
        $content=View::render("admin/dashboard/painel_portfolio/editPortfolio",[
            "id"=>$port[0]['id'],
            "categoria"=>$port[0]['categoria'],
            "descricao"=>$port[0]['descricao'],
            "imagem"=>$port[0]['imagem'],
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Portfolio",$content);  
    }

    public function update($id){
        $regist['categoria']=filter_input(INPUT_POST,'categoria',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $regist['imagem']='';
        $file_upload=$this->verify_file();
        $verify_port=$this->portfolio->getPortfolioById($id);
        if(!empty($file_upload)):
            if(move_uploaded_file($file_upload['tmp'], $file_upload['folder'].$file_upload['new_name'])):
                $regist['imagem']=$file_upload['new_name'];
                $port=$this->portfolio->update($regist, $id);
                $this->status='<h5><br/>Portfolio editado!</h5>';
                $this->edit($id);
                //header('location:'.DIRPAGE."/dashboard-admin/portfolio");
            else:             
                $this->status='<h5><br/>Erro inesperado, o portfolio não foi editado!<br/>Verifique os campos.</h5>';
                $this->edit($id);
            endif;
        else:
            if($file_upload==[]  && $verify_port[0]['imagem']!=NULL):
                $regist['imagem']=$verify_port[0]['imagem'];
                $portfolio=$this->portfolio->update($regist, $id);
                $this->status='<h5><br/>Portfolio editado!</h5>';
                $this->edit($id);
            else:
                $this->status='<h5><br/>Portfolio não foi editado!<br/>Verifique os campos.</h5>';
                $this->edit($id);
            endif;
        endif;
    }

    public function delete($id){
        $this->portfolio->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/portfolio");
    }
}