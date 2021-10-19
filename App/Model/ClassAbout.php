<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassAbout extends ClassConnection {
    //retorna lista de utilizadores
    public function getAboutUs(){
        $sql="SELECT * FROM about_us";
        $select=parent::connect_db()->prepare($sql);
        $select->execute();
        if($select->rowCount() > 0):
            return  $select->fetchAll(\PDO::FETCH_ASSOC);
            //return $results;
        else:
            return [];
        endif;
    }
    //pesquisa por id e retorna um utilizador 
    public function getAboutUsById($id){
        $sql="SELECT * FROM about_us WHERE (id = ?)";
        $select=parent::connect_db()->prepare($sql);
        $select->bindValue(1, $id);
        $select->execute();
        if($select->rowCount() > 0):
            return  $select->fetchAll(\PDO::FETCH_ASSOC);
            //return $results;
        else:
            return [];
        endif;
    }
    //metodo que adiciona dados da publicacao.
    public function create($registo){
        $sql="INSERT INTO about_us(id, descricao, imagem) VALUES(?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo['id']);
        $insert->bindValue(2, $registo['descricao']);
        $insert->bindValue(3, $registo['imagem']);
        $insert->execute();
    }
     //metodo que atualiza dados do user.
     public function update($registo, $id){
        $sql="UPDATE about_us SET descricao = ?, imagem = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['descricao']);
        $update->bindValue(2, $registo['imagem']);
        $update->bindValue(3, $id);
        $update->execute();
    }
    //metodo que elimina dados do user.
    public function delete($id){
        $sql="DELETE FROM about_us WHERE id = ?";
        $insert=parent::connect_db()->prepare($sql);;
        $insert->bindValue(1, $id);
        $insert->execute();
    }
}