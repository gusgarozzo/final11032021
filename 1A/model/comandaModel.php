<?php

class comandaModel{
    private $db;

    function __construct(){
        $this->db = new pdo(...);
    }

    function getComandas($mesa){
        $query = $this->db->prepare('SELECT * FROM comanda WHERE nro_mesa = ?');
        $query->execute($mesa);
        $query->fetch(PDO::FETCH_OBJ);
        return $query->rowCount();
    }

    function agregaComanda($comanda, $mesa, $cerrada){
        $query = $this->db->prepare('INSERT INTO comanda (nro_comanda, nro_mesa, $cerrada)');
        $query->execute(array($comanda, $mesa, $cerrada));
        $query->fetch(PDO::FETCH_OBJ);
        return $query->rowCount();
    }
}