<?php

namespace App\Controllers;

class ServicesController{
    
    public function index(){
        echo "index servico controller";
    }
    public function update($par1, $par2){
        echo "update servico controller par1 '{$par1}', par2 '{$par2}'";
    }
}