<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassContact extends ClassConnection {
    //retorna lista de utilizadores
    public function getContacts(){
        $sql="SELECT * FROM contacts";
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
    public function getContactById($id){
        $sql="SELECT * FROM contacts WHERE (id = ?)";
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
        $sql="INSERT INTO contacts(id, telefone1, telefone2, email, facebook, instagram, linkedin, endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo['id']);
        $insert->bindValue(2, $registo['telefone1']);
        $insert->bindValue(3, $registo['telefone2']);
        $insert->bindValue(4, $registo['email']);
        $insert->bindValue(5, $registo['facebook']);
        $insert->bindValue(6, $registo['instagram']);
        $insert->bindValue(7, $registo['linkedin']);
        $insert->bindValue(8, $registo['endereco']);
        $insert->execute();
    }
     //metodo que atualiza dados do contactos.
     public function update($registo, $id){
        $sql="UPDATE contacts SET telefone1 = ?, telefone2 = ?, email = ?, facebook = ?, instagram = ?, linkedin = ?, endereco = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['telefone1']);
        $update->bindValue(2, $registo['telefone2']);
        $update->bindValue(3, $registo['email']);
        $update->bindValue(4, $registo['facebook']);
        $update->bindValue(5, $registo['instagram']);
        $update->bindValue(6, $registo['linkedin']);
        $update->bindValue(7, $registo['endereco']);
        $update->bindValue(8, $id);
        $update->execute();
    }
    //metodo que elimina dados do user.
    public function delete($id){
        $sql="DELETE FROM contacts WHERE id = ?";
        $insert=parent::connect_db()->prepare($sql);;
        $insert->bindValue(1, 1);
        $insert->execute();
    }
}