<?php

namespace App\Controllers;

use App\Model\VerifyAuth;
use App\Utils\View;
use App\Utils\LayoutRender;

class AdminHomeController extends LayoutRender{
    
    public function __construct(){
        //echo "admin controller";
        $verify=new VerifyAuth;
        $verify->verify_user();
        //$this->setTitle("Prestativ - Admin");
    }
    public static function index(){
        //Retorna a pagina padrao(Layout)
        $content= View::render("admin/dashboard/painelhome",[
        "URL"=>DIRPAGE,
        "nome"=>"Rogerio Rona Joao Tuzolana",
        "discricao"=>"Programador",
        "servicos"=>"web-sites"
        ]);
        //Retorna a view da pagina
        echo parent::getLayout("admin/dashboard/dashlayout","Prestativ | Painel-Admin",$content);
    }

}