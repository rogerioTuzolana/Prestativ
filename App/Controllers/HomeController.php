<?php

namespace App\Controllers;

use App\Utils\View;
use App\Utils\LayoutRender;
use App\Model\ClassAbout;
use App\Model\ClassContact;
use App\Model\ClassSlide;
use App\Model\ClassTeam;
use App\Model\ClassServices;
use App\Model\ClassPortfolio;

class HomeController extends LayoutRender{
    private $service;
    private $about;
    private $contact;
    private $team;
    private $slide;
    private $portfolio;
    public function __construct(){
        $this->service = new ClassServices;
        $this->about = new ClassAbout;
        $this->contact = new ClassContact;
        $this->portfolio = new ClassPortfolio;
        $this->team = new ClassTeam;
        $this->slide = new ClassSlide;
    }

    public static function itemsSlide($slide){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $active='active';
        foreach($slide as $sli):
            $itens.= View::render("home/home_slide/home_items_slide",[
                "imagem"=>$sli['imagem']??'',
                "descricao"=>$sli['descricao']??'',
                "titulo"=>$sli['titulo']??'',
                "active"=>$active,
                "URL"=>DIRPAGE
            ]);
            $active="";   
        endforeach;
        
        return $itens;
    }
    public static function itemsAbout($about){
        //Retorna a pagina padrao(Layout)
        $itens="";
        foreach($about as $abt):
            $itens.= View::render("home/home_about/home_items_about",[
            "imagem"=>$abt['imagem']??'',
            "descricao"=>$abt['descricao']??'',
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public static function itemsServices($services){
        //Retorna a pagina padrao(Layout)
        $itens="";
        foreach($services as $service):
            $itens.= View::render("home/home_service/home_items_service",[
            "servico"=>$service['servico']??'',
            "categoria"=>$service['categoria']??'',
            "descricao"=>$service['descricao']??'',
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public static function itemsServices2($services){
        //Retorna a pagina padrao(Layout)
        $itens="";
        foreach($services as $service):
            $itens.= View::render("home/home_service/home_items_service2",[
            "servico"=>$service['servico']??'',
            "categoria"=>$service['categoria']??'',
            "descricao"=>$service['descricao']??'',
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public static function itemsTeam($team){
        //Retorna a pagina padrao(Layout)
        $itens="";
        foreach($team as $tm):
            $itens.= View::render("home/home_team/home_items_team",[
            "imagem"=>$tm['imagem']??'',
            "cargo"=>$tm['cargo']??'',
            "nome"=>$tm['nome']??'',
            "facebook"=>$tm['facebook']??'',
            "linkedin"=>$tm['linkedin']??'',
            "twitter"=>$tm['twitter']??'',
            "instagram"=>$tm['instagram']??'',
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public static function itemsContacts($contacts){
        //Retorna a pagina padrao(Layout)
        $itens="";
        foreach($contacts as $contact):
            $itens.= View::render("home/home_contacts/home_items_contacts",[
            "endereco"=>$contact['endereco']??'',
            "email"=>$contact['email']??'',
            "telefone1"=>$contact['telefone1']??'',
            "telefone2"=>$contact['telefone2']??'',
            "facebook"=>$contact['facebook']??'',
            "linkedin"=>$contact['linkedin']??'',
            "twitter"=>$contact['twitter']??'',
            "instagram"=>$contact['instagram']??'',
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public static function itemsPortfolio($portfolio){
        //Retorna a pagina padrao(Layout)
        $itens="";
        foreach($portfolio as $port):
            $itens.= View::render("home/home_portfolio/home_items_portfolio",[
            "imagem"=>$port['imagem']??'',
            "categoria"=>$port['categoria']??'',
            "descricao"=>$port['descricao']??'',
            "URL"=>DIRPAGE
            ]);   
        endforeach;
        
        return $itens;
    }
    public static function categoryPortfolio($portfolio){
        //Retorna a pagina padrao(Layout)
        $itens="";
        $i=1;
        $category=[];
        foreach($portfolio as $port):
            if(array_search($port['categoria'],$category)==NULL):
                $category[$i]=$port['categoria'];

                $itens.= View::render("home/home_portfolio/home_category_portfolio",[
                "category"=>$port['categoria']??'',
                "descricao"=>$port['descricao']??'',
                "URL"=>DIRPAGE
                ]);
                $i++; 
            endif;  
        endforeach;
        return $itens;
    }
    public function index(){
        //Retorna a pagina padrao(Layout)
        $content_slide= View::render("home/home_slide/home_slide",[
            "items"=>self::itemsSlide($this->slide->getSlide()),
            "URL"=>DIRPAGE
        ]);
        $content_about= View::render("home/home_about/home_about",[
            "items"=>self::itemsAbout($this->about->getAboutUs()),
            "URL"=>DIRPAGE
        ]);
        $content_service= View::render("home/home_service/home_service",[
            "items"=>self::itemsServices($this->service->getServices()),
            "URL"=>DIRPAGE
        ]);

        $content_team= View::render("home/home_team/home_team",[
            "items"=>self::itemsTeam($this->team->getTeam()),
            "URL"=>DIRPAGE
        ]);
        $content_contacts= View::render("home/home_contacts/home_contacts",[
            "items"=>self::itemsContacts($this->contact->getContacts()),
            "URL"=>DIRPAGE
        ]);
        $content_portfolio= View::render("home/home_portfolio/home_portfolio",[
            "items"=>self::itemsPortfolio($this->portfolio->getPortfolio()),
            "category"=>self::categoryPortfolio($this->portfolio->getPortfolio()),
            "URL"=>DIRPAGE
        ]);
        $contents=[
            "endereco"=>$this->contact->getContacts()[0]['endereco']??'',
            "telefone1"=>$this->contact->getContacts()[0]['telefone1']??'',
            "telefone2"=>$this->contact->getContacts()[0]['telefone2']??'',
            "twitter"=>$this->contact->getContacts()[0]['twitter']??'',
            "facebook"=>$this->contact->getContacts()[0]['facebook']??'',
            "instagram"=>$this->contact->getContacts()[0]['instagram']??'',
            "linkedin"=>$this->contact->getContacts()[0]['linkedin']??'',
            "email"=>$this->contact->getContacts()[0]['email']??'',
            "slide"=>$content_slide,
            "about"=>$content_about,
            "services"=>$content_service,
            "services2"=>self::itemsServices2($this->service->getServices())??'',
            "portfolio"=>$content_portfolio,
            "team"=>$content_team,
            "contacts"=>$content_contacts
        ];
        //Retorna a view da pagina
        echo parent::getLayout2("home/homelayout","Prestativ-Site",$contents);
    }
}