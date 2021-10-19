<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassSlide extends ClassConnection {
    //retorna lista de portfolio
    public function getSlide(){
        $sql="SELECT * FROM slide";
        $select=parent::connect_db()->prepare($sql);
        $select->execute();
        if($select->rowCount() > 0):
            return  $select->fetchAll(\PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    //pesquisa por id e retorna um Slide 
    public function getSlideById($id){
        $sql="SELECT * FROM slide WHERE (id = ?)";
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
    //metodo que adiciona dados do portfolio.
    public function create($registo){
        $sql="INSERT INTO slide(titulo, descricao, imagem) VALUES(?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo['titulo']);
        $insert->bindValue(2, $registo['descricao']);
        $insert->bindValue(3, $registo['imagem']);
        $insert->execute();
    }
     //metodo que atualiza dados do portfolio.
     public function update($registo, $id){
        $sql="UPDATE slide SET titulo=?, descricao = ?, imagem = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['titulo']);
        $update->bindValue(2, $registo['descricao']);
        $update->bindValue(3, $registo['imagem']);
        $update->bindValue(4, $id);
        $update->execute();
    }
    //metodo que elimina dados do portfolio.
    public function delete($id){
        $sql="DELETE FROM slide WHERE id = ?";
        $delete=parent::connect_db()->prepare($sql);;
        $delete->bindValue(1, $id);
        $delete->execute();
    }
}