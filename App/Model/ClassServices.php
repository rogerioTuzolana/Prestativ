<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassServices extends ClassConnection {
    //retorna lista de utilizadores
    public function getServices(){
        $sql="SELECT * FROM services";
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
    public function getServiceById($id){
        $sql="SELECT * FROM services WHERE (id = ?)";
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
    //metodo que adiciona dados do user.
    public function create($registo){
        $sql="INSERT INTO services(servico, categoria, descricao, estado) VALUES(?, ?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo['servico']);
        $insert->bindValue(2, $registo['categoria']);
        $insert->bindValue(3, $registo['descricao']);
        $insert->bindValue(4, $registo['estado']);
        $insert->execute();
    }
     //metodo que atualiza dados do user.
     public function update($registo, $id){
        $sql="UPDATE services SET servico = ?, categoria = ?, descricao = ?, estado = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['servico']);
        $update->bindValue(2, $registo['categoria']);
        $update->bindValue(3, $registo['descricao']);
        $update->bindValue(4, $registo['estado']);
        $update->bindValue(5, $id);
        $update->execute();
    }
    //metodo que elimina dados do user.
    public function delete($id){
        $sql="DELETE FROM services WHERE id = ?";
        $insert=parent::connect_db()->prepare($sql);;
        $insert->bindValue(1, $id);
        $insert->execute();
    }
}