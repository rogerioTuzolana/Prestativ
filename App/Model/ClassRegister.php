<?php

namespace App\Model;
use App\Model\ClassConnection;
use App\Model\Register;

abstract class ClassRegister extends ClassConnection {

    //metodo que adiciona dados do user.
    public function create(Register $registo){
        $sql="INSERT INTO users(nome, email, nivel, password) VALUES(?, ?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo->getNome());
        $insert->bindValue(2, $registo->getEmail());
        $insert->bindValue(3, $registo->getNivel());
        $insert->bindValue(4, $registo->getPassword());
        $insert->execute();
    }
    //metodo que lista todos os dados
    public function read(){
        $sql="SELECT * FROM users";
        $select=parent::connect_db()->prepare($sql);
        $select->execute();
        if($select->rowCount() > 0):
            $results = $select->fetchAll(\PDO::FETCH_ASSOC);
            return $results;
        else:
            return [];
        endif;
    }
    //metodo que seleciona dados de um user.
    public function find($id){

    }
    //metodo que atualiza dados do user.
    public function update(Register $registo, $id){
        $sql="UPDATE users SET nome = ?, email = ?, nivel = ?, password = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo->getNome());
        $update->bindValue(2, $registo->getEmail());
        $update->bindValue(3, $registo->getNivel());
        $update->bindValue(4, $registo->getPassword());
        $update->bindValue(5, $registo->getId());
        $update->execute();
    }
    //metodo que elimina dados do user.
    public function delete($id){
        $sql="DELETE FROM users WHERE id = ?";
        $insert=parent::connect_db()->prepare($sql);;
        $insert->bindValue(1, $id);
        $insert->execute();
    }

}