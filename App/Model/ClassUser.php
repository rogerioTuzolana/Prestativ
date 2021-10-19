<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassUser extends ClassConnection {
    //Dado um email verifica se existe um utilizador
    public function getUser($email){
        $sql="SELECT * FROM users  WHERE (email = ?)";
        $select=parent::connect_db()->prepare($sql);
        $select->bindValue(1, $email);
        $select->execute();
        $datas=$select->fetch(\PDO::FETCH_ASSOC);
        if($select->rowCount() > 0):
            $row=$select->rowCount();
            return $arry_datas=[
                'data' => $datas,
                'row'=>$row
            ];
        else:
            return [];
        endif;
    }
    //retorna lista de utilizadores
    public function getUsers(){
        $sql="SELECT * FROM users";
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
    public function getUserById($id){
        $sql="SELECT * FROM users WHERE (id = ?)";
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
     //metodo que atualiza dados do user.
     public function update($registo, $id){
        $sql="UPDATE users SET nome = ?, email = ?, nivel = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['nome']);
        $update->bindValue(2, $registo['email']);
        $update->bindValue(3, $registo['nivel']);
        $update->bindValue(4, $id);
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