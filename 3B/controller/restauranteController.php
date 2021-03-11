<?php

require_once '../model/comandaModel.php';
require_once '../model/facturacionModel.php';
// require_once '../view/restauranteView.php'

class restauranteController{
    private $facturacionModel;
    //private $view;

    public function __construct(){
        $this->facturacionModel = new facturacionModel();
        //$this->view = new restauranteView();
    }

    function tablaController($params=null){
        // Ingreso el año requerido
        if(isset($_POST['año'])){
            $año = $_POST['año'];

            // Traigo todas las facturas cargadas para ese año
            $facturas = $this->facturacionModel->getFacturasPorAño($año);

            // Si la búsqueda trae resultados...
            if($facturas){
                // Recorro todos los datos que me trae la BBBDD
                foreach ($facturas as $factura){
                    // Busco los montos de las facturas de un mes determinado
                    $monto = $this->facturacionModel->getMontoTotal($factura->mes);
    
                    // Si la busqueda trae resultados
                    if ($monto){
                        // Sumo todos los valores y los guardo en una variable
                        $montoTotal = array_sum($monto);

                        $this->view->imprimeTabla($factura->mes, $factura->dia, $factura->año, $factura->monto_final, $montoTotal);
                    }else{
                        $this->view->mensaje("El mes $factura->mes no tiene facturas cargadas");
                    }
                    
                }
            }else{
                $this->view->mensaje("El año $año no tiene facturas cargadas");
            }
            
        }
    }
}