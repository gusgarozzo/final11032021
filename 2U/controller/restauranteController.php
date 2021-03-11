<?php

require_once '../model/comandaModel.php';
require_once '../model/facturacionModel.php';
// require_once '../view/restauranteView.php'

class restauranteController{
    private $comandaModel;
    private $facturacionModel;
    //private $view;

    public function __construct(){
        $this->comandaModel = new comandaModel();
        $this->facturacionModel = new facturacionModel();
        //$this->view = new restauranteView();
    }

    function comandaController($params = null){
        // Capto los datos de la comanda en cuestión
        if (isset($_GET['nro_comanda'])){
            $comanda = $_GET['nro_comanda'];
            $cerrada = true;
            
            // Chequeo que la comanda exista
            $chequeaComanda = $this->comandaModel->chequeaComanda($comanda);

            // Itero sobre los datos de la comanda en la base de datos
            foreach ($chequeaComanda as $dato){
                // Si la comanda ingresada está abierta, procedo a hacer el cierre de la misma
                if ($dato->cerrada === false){
                    $cierraComanda = $this->comandaModel->cierraComanda($cerrada, $comanda);

                    if ($cierraComanda > 0){
                        $this->view->mensaje("Comanda cerrada exitosamente");
                        if(isset($_POST['dia']) && ($_POST['mes']) && ($_POST['año']) &&
                            ($_POST['monto_final'])){
                            $dia = $_POST['dia'];
                            $mes = $_POST['mes'];
                            $año = $_POST['año'];
                            $monto = $_POST['monto_final'];
                            
                            // Para obtener el ide de la comanda, busco el mismo con el numero de comanda ingresado por el usuario
                            $id_comanda = $this->comandaModel->getComandaByNro($comanda);
                            
                            // Preparo el ticket para ser creado al momento de la facturación con los datos ingresados oportunamente
                            $ticket = $this->facturacionModel->getTicket($id_comanda);
                            
                            // DESCUENTOS
                            if ($monto < 1000){
                                // Si el monto es menor que 1000, no se realiza 
                                $descuento = 'No se realiza descuento';
                                // Por lo tanto, facturo tal y como está
                                $facturacion = $this->facturacionModel->ingresaFacturacion($dia, $mes, $año, $monto, $id_comanda);
                                if($facturacion > 0){
                                    // Si el proceso de facturación se realizo correctamente, imprimo el ticket
                                    $this->view->muestraTicket($ticket, $descuento);
                                }else{
                                    $this->view->mensaje("Hubo un inconveniente al facturar, revise los campos ingresados");
                                }
                            }elseif($monto > 1000){
                                // Si el monto es mayor a 1000, realizo descuento del 10%
                                $descuento = 'Se realizó un descuento del 10%';
                                $montoFinal = $monto - (($monto*10)/100); 

                                // Envio los datos a la base de datos
                                $facturacion = $this->facturacionModel->ingresaFacturacion($dia, $mes, $año, $montoFinal, $id_comanda);
                                if($facturacion > 0){
                                    // Si el proceso de facturación se realizo correctamente, imprimo el ticket
                                    $this->view->muestraTicket($ticket, $descuento);
                                }else{
                                    $this->view->mensaje("Hubo un inconveniente al facturar, revise los campos ingresados");
                                }
                            }
                            
                        }
                    }
                }
            }
            
            
        }
    }
}