<?php

class facturacionModel{
    private $db;

    function __construct(){
        $this->db = new pdo(...);
    }

    function getFacturasPorAño($año){
        // Traigo todas los datos del año y los agrupo por mes
        $query = $this->db->prepare('SELECT * FROM facturacion WHERE año = ? GROUP BY mes'); 
        $query->execute($año);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getMontoTotal($mes){
        $query = $this->db->prepare('SELECT monto_final FROM facturacion WHERE mes = ?');
        $query->execute($mes);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}