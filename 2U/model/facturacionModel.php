<?php

class facturacionModel{
    private $db;

    function __construct(){
        $this->db = new pdo(...);
    }

    function ingresaFacturacion($dia, $mes, $año, $monto, $id_comanda){
        $query = $this->db->prepare('INSERT INTO facturacion (dia, mes, año, monto_final, id_comanda)
            VALUES(?,?,?,?,?)');
        $query->execute($dia, $mes, $año, $monto, $id_comanda);
        $query->fetch(PDO::FETCH_OBJ);
        return $query->rowCount();
    }

    function getTicket($id_comanda){
        $query = $this->db->prepare('SELECT * FROM facturacion INNER JOIN item WHERE item.id_comanda = facturacion.id_comanda');
        $query->execute($id_comanda);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}