<?php

namespace App\Model;

class Register {
    
    private $id;
    private $nome;
    private $email;
    private $nivel;
    private $imagem;
    private $password;
    
    public function getId(){return $this->id; }
    public function setId($id){$this->id=$id;}

    public function getNome(){return $this->nome; }
    public function setNome($nome){$this->nome=$nome;}

    public function getEmail(){return $this->email; }
    public function setEmail($email){$this->email=$email;}

    public function getNivel(){return $this->nivel; }
    public function setNivel($nivel){$this->nivel=$nivel;}

    public function getImagem(){return $this->imagem;}
    public function setImagem($imagem){$this->imagem=$imagem;}

    public function getPassword(){
        return $this->password; 
    }
    public function setPassword($password){$this->password=$password;}
    
}