<?php

namespace App\Model;
use App\Model\ClassConnection;


class ClassTeam extends ClassConnection {
    //retorna lista de team
    public function getTeam(){
        $sql="SELECT * FROM team";
        $select=parent::connect_db()->prepare($sql);
        $select->execute();
        if($select->rowCount() > 0):
            return  $select->fetchAll(\PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    //pesquisa por id e retorna um team 
    public function getTeamById($id){
        $sql="SELECT * FROM team WHERE (id = ?)";
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
    //metodo que adiciona dados do team.
    public function create($registo){
        $sql="INSERT INTO team(nome, cargo, imagem, facebook, instagram, twitter, linkedin) VALUES(?, ?, ?, ?, ?, ?, ?)";
        $insert=parent::connect_db()->prepare($sql);
        $insert->bindValue(1, $registo['nome']);
        $insert->bindValue(2, $registo['cargo']);
        $insert->bindValue(3, $registo['imagem']);
        $insert->bindValue(4, $registo['facebook']);
        $insert->bindValue(5, $registo['instagram']);
        $insert->bindValue(6, $registo['twitter']);
        $insert->bindValue(7, $registo['linkedin']);
        $insert->execute();
    }
     //metodo que atualiza dados do team.
     public function update($registo, $id){
        $sql="UPDATE team SET nome = ?, cargo = ?, imagem = ?, facebook = ?, instagram = ?, twitter = ?, linkedin = ? WHERE id = ?";
        $update=parent::connect_db()->prepare($sql);
        $update->bindValue(1, $registo['nome']);
        $update->bindValue(2, $registo['cargo']);
        $update->bindValue(3, $registo['imagem']);
        $update->bindValue(4, $registo['facebook']);
        $update->bindValue(5, $registo['instagram']);
        $update->bindValue(6, $registo['twitter']);
        $update->bindValue(7, $registo['linkedin']);
        $update->bindValue(8, $id);
        $update->execute();
    }
    //metodo que elimina dados do team.
    public function delete($id){
        $sql="DELETE FROM team WHERE id = ?";
        $delete=parent::connect_db()->prepare($sql);;
        $delete->bindValue(1, $id);
        $delete->execute();
    }
}