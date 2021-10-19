<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Model\ClassContact;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminContactsController extends LayoutRender{
    private $contacts;
    private $status;
    private $op_route;
    private $op_route_info;
    public function __construct(){
        $this->contacts=new ClassContact;
        $this->status='';
        $this->op_route='add';
        $this->op_route_info="Adicionar<b>+</b>";
        $verify=new VerifyAuth;
        $verify->verify_user();
    }
    public static function items($contacts){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $estado="";
        foreach($contacts as $contact):
            $itens.= View::render("admin/dashboard/painel_contacts/itemsContacts",[
            "id"=>$contact['id'],
            "telefone1"=>$contact['telefone1'],
            "telefone2"=>$contact['telefone2'],
            "email"=>$contact['email'],
            "facebook"=>$contact['facebook'],
            "instagram"=>$contact['instagram'],
            "linkedin"=>$contact['linkedin'],
            "endereco"=>$contact['endereco'],
            "URL"=>DIRPAGE
            ]);
            
        endforeach;
        
        return $itens;
    }
    public function index(){
        if (!empty($this->contacts->getContacts())) {
            $this->op_route='edit/1';
            $this->op_route_info="Editar";
        }
        $content= View::render("admin/dashboard/painel_contacts/contacts",[
            "itens"=>self::items($this->contacts->getContacts()),
            "URL"=>DIRPAGE,
            "op_route"=>$this->op_route,
            "op_route_info"=>$this->op_route_info
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Contactos",$content);  
    }
    public function add(){;
        //$user=$this->users->getUserById($id);
        $content=View::render("admin/dashboard/painel_contacts/addContacts",[
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Contactos",$content);  
    }

    public function store(){
        $registo['id']='1';
        $registo['telefone1']=filter_input(INPUT_POST,'telefone1',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['telefone2']=filter_input(INPUT_POST,'telefone2',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['email']=filter_input(INPUT_POST,'email',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['facebook']=filter_input(INPUT_POST,'facebook',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['instagram']=filter_input(INPUT_POST,'instagram',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['linkedin']=filter_input(INPUT_POST,'linkedin',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['endereco']=filter_input(INPUT_POST,'endereco',FILTER_SANITIZE_SPECIAL_CHARS);        
        if(!empty($registo['telefone1']) && !empty($registo['telefone2']) && !empty($registo['endereco'])){
            $contact=$this->contacts->create($registo);
            $this->status='<h5><br/>Contacto adicionado!</h5>';
            $this->add();
        }else{
            $this->status='<h5><br/>Contacto não adicionado!<br/>Verifique os campos.</h5>';
            $this->add();
        }
    }

    public function edit($id){
        $contact=$this->contacts->getContactById($id);
        $content=View::render("admin/dashboard/painel_contacts/editContacts",[
            "id"=>$contact[0]['id'],
            "telefone1"=>$contact[0]['telefone1'],
            "telefone2"=>$contact[0]['telefone2'],
            "email"=>$contact[0]['email'],
            "facebook"=>$contact[0]['facebook'],
            "instagram"=>$contact[0]['instagram'],
            "linkedin"=>$contact[0]['linkedin'],
            "endereco"=>$contact[0]['endereco'],
            "URL"=>DIRPAGE,
            "status"=>$this->status
        ]);

        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin-Editar Contactos",$content);  
    }

    public function update($id){
        $registo['telefone1']=filter_input(INPUT_POST,'telefone1',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['telefone2']=filter_input(INPUT_POST,'telefone2',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['email']=filter_input(INPUT_POST,'email',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['facebook']=filter_input(INPUT_POST,'facebook',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['instagram']=filter_input(INPUT_POST,'instagram',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['linkedin']=filter_input(INPUT_POST,'linkedin',FILTER_SANITIZE_SPECIAL_CHARS);
        $registo['endereco']=filter_input(INPUT_POST,'endereco',FILTER_SANITIZE_SPECIAL_CHARS);        
        if(!empty($registo['telefone1']) && !empty($registo['telefone2']) && !empty($registo['endereco'])){
            $contact=$this->contacts->update($registo, $id);
            $this->status='<h5><br/>Contactos editados!</h5>';
            header('location:'.DIRPAGE."/dashboard-admin/contacts");
        }else{
            $this->status='<h5><br/>Contactos não foram editados!<br/>Verifique os campos.</h5>';
            $this->edit($id);
        }
    }
    public function delete($id){
        $this->contacts->delete($id);
        header('location:'.DIRPAGE."/dashboard-admin/contacts");
    }
}