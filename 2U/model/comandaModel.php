<?php

class comandaModel{
    private $db;

    function __construct(){
        $this->db = new pdo(...);
    }

    function chequeaComanda($comanda){
        $query = $this->db->prepare('SELECT * FROM comanda WHERE nro_comanda = ?');
        $query->execute($comanda);
        return $query->fetch(PDO::FETCH_OBJ);
    }


    function cierraComanda($cerrada, $comanda){
        $query = $this->db->prepare('UPDATE comanda SET cerrada = ? WHERE comanda.nro_comanda = ?');
        $query->execute($cerrada, $comanda);
        $query->fetch(PDO::FETCH_OBJ);
        return $query->rowCount();
    }

    function getComandaByNro($comanda){
        $query = $this->db->prepare('SELECT id FROM comanda WHERE nro_comanda = ?');
        $query->execute($comanda);
        return $query->fetch(PDO::FETCH_OBJ);
    }
}