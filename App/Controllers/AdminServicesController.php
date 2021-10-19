<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassServices;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminServicesController extends LayoutRender{
    private $services;
    private $status;
    public function __construct(){
        $this->services=new ClassServices;
        $this->status='';
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($services){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $estado="";
        foreach($services as $service):
            $itens.= View::render("admin/dashboard/painel_services/itemsServices",[
            "id"=>$service['id'],
            "servico"=>$service['servico'],
            "categoria"=>$service['categoria'],
            "descricao"=>$service['descricao'],
            "estado"=>($service['estado']==1)?'Activo':'Inativo',
            "time"=>$service['time'],
            "URL"=>DIRPAGE
            ]);
            
        endforeach;
        
        return $itens;
    }
    public function index(){

        $content=View::render("admin/dashboard/painel_services/services",[
            "itens"=>self::items($this->services->getServices()),
            "URL"=>DIRPAGE
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Serviços",$content);  
    }
    public function add(){;
        //$user=$this->users->getUserById($id);
        $content=View::render("admin/dashboard/painel_services/addService",[
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Adicionar Serviço",$content);  
    }

    public function store(){
        $registo['servico']=filter_input(INPUT_POST,'servico',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $registo['categoria']=filter_input(INPUT_POST,'categoria',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $registo['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $registo['estado']=filter_input(INPUT_POST,'estado',FILTER_SANITIZE_SPECIAL_CHARS)??'0';
        if($registo['servico']!=NULL && $registo['categoria']!=NULL && $registo['estado']!=NULL){
            $service=$this->services->create($registo);
            $this->status='<h5><br/>Serviço adicionado!</h5>';
            $this->add();
        }else{
            $this->status='<h5><br/>Serviço não adicionado!<br/>Verifique os campos.</h5>';
            $this->add();
        }
    }

    public function edit($id){
        $nivel=[];
        $service=$this->services->getServiceById($id);
        if($service[0]['estado']=='1'):
            $estado['activo']='selected';
            $estado['inactivo']='';
        else:
            $estado['inactivo']='selected';
            $estado['activo']='';
        endif;
        $content=View::render("admin/dashboard/painel_services/editServices",[
            "id"=>$service[0]['id'],
            "servico"=>$service[0]['servico'],
            "categoria"=>$service[0]['categoria'],
            "descricao"=>$service[0]['descricao'],
            "estado_activo"=>$estado['activo'],
            "estado_inactivo"=>$estado['inactivo'],
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Serviço",$content);  
    }

    public function update($id){
        $registo['servico']=filter_input(INPUT_POST,'servico',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $registo['categoria']=filter_input(INPUT_POST,'categoria',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $registo['descricao']=filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_SPECIAL_CHARS)??NULL;
        $registo['estado']=filter_input(INPUT_POST,'estado',FILTER_SANITIZE_SPECIAL_CHARS)??0;
        if($registo['servico']!=NULL && $registo['categoria']!=NULL){
            $service=$this->services->update($registo, $id);
            $this->status='<h5><br/>Serviço editado!</h5>';
            header('location:'.DIRPAGE."/dashboard-admin/services");
        }else{
            $this->status='<h5><br/>Serviço não foi editado!<br/>Verifique os campos.</h5>';
            $this->edit($id);
        }
    }
    public function delete($id){
        $this->services->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/services");
    }
}