<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassBlog extends ClassConnection {
    //retorna lista de utilizadores
    public function getBlog(){
        $sql="SELECT * FROM blog";
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
    public function getBlogById($id){
        $sql="SELECT * FROM blog WHERE (id = ?)";
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
        $sql="INSERT INTO blog(titulo, descricao, imagem) VALUES(?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo['titulo']);
        $insert->bindValue(2, $registo['descricao']);
        $insert->bindValue(3, $registo['imagem']);
        $insert->execute();
    }
     //metodo que atualiza dados do user.
     public function update($registo, $id){
        $sql="UPDATE blog SET titulo = ?, descricao = ?, imagem = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['titulo']);
        $update->bindValue(2, $registo['descricao']);
        $update->bindValue(3, $registo['imagem']);
        $update->bindValue(4, $id);
        $update->execute();
    }
    //metodo que elimina dados do user.
    public function delete($id){
        $sql="DELETE FROM blog WHERE id = ?";
        $insert=parent::connect_db()->prepare($sql);;
        $insert->bindValue(1, $id);
        $insert->execute();
    }
}